<?php

namespace App\EventListener;

use App\Entity\Notification;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;

final class UpdatedAtListener
{
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (
            $entity instanceof Notification
            || $entity instanceof Product
            || $entity instanceof User
        ) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
