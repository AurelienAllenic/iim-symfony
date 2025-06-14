<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;

class NotificationManager
{
    private $em;
    private $notificationRepository;

    public function __construct(EntityManagerInterface $manager, NotificationRepository $notificationRepository)
    {
        $this->em = $manager;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @param string $type ('Création', 'Modification', 'Suppression')
     * @param Product $product
     * @param User $user
     */
    public function addProductNotification(string $type, Product $product, User $user): void
    {
        $notification = new Notification();
        $notification->setLabel('[' . $type . '] produit: ' . $product->getName());
        $notification->setUser($user);
        $this->em->persist($notification);
        $this->em->flush();
    }
}
