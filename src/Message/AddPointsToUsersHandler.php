<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\AddPointsToUsers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddPointsToUsersHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(AddPointsToUsers $message)
    {
        $users = $this->entityManager->getRepository(User::class)->findBy(['actif' => true]);
        foreach ($users as $user) {
            $user->setPoints($user->getPoints() + 1000);
        }
        $this->entityManager->flush();
    }
}
