<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\NotificationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products_list')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/product/new', name: 'app_product_create')]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        NotificationManager $notificationManager
    ): Response {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);

            $notificationManager->addProductNotification('creation', $product, $this->getUser());

            $this->addFlash('success', 'Produit ajouté avec succès !');
            return $this->redirectToRoute('app_products_list');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Créer un produit',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/product/{id}/edit', name: 'app_product_edit')]
    public function edit(
        Request $request,
        Product $product,
        NotificationManager $notificationManager
    ): Response {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notificationManager->addProductNotification('modification', $product, $this->getUser());

            $this->addFlash('success', 'Produit modifié avec succès.');
            return $this->redirectToRoute('app_products_list');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier le produit',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/product/{id}/delete', name: 'app_product_delete')]
    public function delete(
        Product $product,
        EntityManagerInterface $em,
        NotificationManager $notificationManager
    ): Response {
        $em->remove($product);

        $notificationManager->addProductNotification('suppression', $product, $this->getUser());
        $em->flush();

        $this->addFlash('success', 'Produit supprimé.');
        return $this->redirectToRoute('app_products_list');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/product/{id}/buy', name: 'app_product_buy', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function buy(Product $product, EntityManagerInterface $em): Response
    {
        /** @var User */
        $user = $this->getUser();

        // Vérifie si le produit a déjà été acheté
        if ($product->getUser() !== null) {
            $this->addFlash('warning', 'Ce produit a déjà été acheté.');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        // Vérifie que l’utilisateur n'est pas désactivé
        if (!$user->isActive()) {
            $this->addFlash('error', 'Impossible d\'acheter étant inactif.');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        // Vérifie les points de l’utilisateur
        if ($user->getPoints() < $product->getPrice()) {
            $this->addFlash('error', 'Points insuffisants pour acheter ce produit.');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        $user->setPoints($user->getPoints() - $product->getPrice());
        $product->setUser($user);

        $notification = new Notification();
        $notification->setLabel('[achat] produit: ' . $product->getName());
        $notification->setUser($user);
        $em->persist($notification);

        $em->flush();

        $this->addFlash('success', 'Produit acheté avec succès !');
        return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
    }
}
