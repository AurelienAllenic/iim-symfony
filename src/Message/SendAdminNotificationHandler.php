<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Message\SendAdminNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendAdminNotificationHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(SendAdminNotification $message)
    {
        $admin = $this->entityManager->getRepository(User::class)->find($message->getAdminId());
        $notification = new Notification();
        $notification->setLabel(sprintf(
            '%s sur %s le %s',
            $message->getActionType(),
            $message->getEntityName(),
            $message->getActionDate()->format('d/m/Y H:i')
        ));
        $notification->setUser($admin);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}
