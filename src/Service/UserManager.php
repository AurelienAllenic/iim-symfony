<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private $em;
    private $userRepository;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->em = $manager;
        $this->userRepository = $userRepository;
    }

    public function addPointsToActiveUsers($points): void
    {
        $activeUsers = $this->userRepository->findBy(['isActive' => true]);

        foreach ($activeUsers as $user) {
            /** @var User $user */
            $user->setPoints($user->getPoints() + $points);
        }

        $this->em->flush();
    }
}