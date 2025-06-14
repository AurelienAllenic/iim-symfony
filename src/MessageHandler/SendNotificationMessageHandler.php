<?php

namespace App\MessageHandler;

use App\Message\SendNotificationMessage;
use App\Entity\Notification;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendNotificationMessageHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em
    ) {}

    public function __invoke(SendNotificationMessage $message)
    {
        $admins = $this->userRepository->findAllAdmins();

        foreach ($admins as $admin) {
            $notif = new Notification();
            $notif->setLabel($message->getLabel());
            $notif->setUser($admin);

            $this->em->persist($notif);
        }

        $this->em->flush();
    }
}
