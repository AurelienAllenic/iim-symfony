<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\SendNotificationMessage;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/users', name: 'app_users_list')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user/{id}/toggle-active', name: 'app_user_toggle_active')]
    public function toggleActive(User $user, EntityManagerInterface $em): Response
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('warning', 'Impossible de modifier un administrateur.');
            return $this->redirectToRoute('app_users_list');
        }

        $user->setIsActive(!$user->isActive());
        $em->flush();

        $this->addFlash('success', 'Utilisateur mis à jour.');
        return $this->redirectToRoute('app_users_list');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/users/add-points-active', name: 'app_users_add_points_active')]
    public function addPointsToActiveUsers(
        UserManager $userManager,
        MessageBusInterface $bus
    ): Response
    {
        $userManager->addPointsToActiveUsers(1000);

        $bus->dispatch(new SendNotificationMessage(
            '1000 points ont été ajoutés aux utilisateurs actifs.'
        ));

        $this->addFlash('success', '1000 points ajoutés à tous les utilisateurs actifs.');

        return $this->redirectToRoute('app_users_list');
    }
}
