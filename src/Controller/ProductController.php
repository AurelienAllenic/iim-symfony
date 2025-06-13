<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
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
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);

            $notification = new Notification();
            $notification->setLabel('[creation] produit: ' . $product->getName());
            $notification->setUser($this->getUser());
            $em->persist($notification);

            $em->flush();

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
    public function edit(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification = new Notification();
            $notification->setLabel('[modification] produit: ' . $product->getName());
            $notification->setUser($this->getUser());
            $em->persist($notification);

            $em->flush();

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
    public function delete(Product $product, EntityManagerInterface $em): Response
    {
        $em->remove($product);

        $notification = new Notification();
        $notification->setLabel('[suppression] produit: ' . $product->getName());
        $notification->setUser($this->getUser());
        $em->persist($notification);

        $em->flush();

        $this->addFlash('success', 'Produit supprimé.');
        return $this->redirectToRoute('app_products_list');
    }
}
