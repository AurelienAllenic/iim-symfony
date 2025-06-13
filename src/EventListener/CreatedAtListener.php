<?php

namespace App\EventListener;

use App\Entity\Notification;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\Event\PrePersistEventArgs;

final class CreatedAtListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (
            $entity instanceof Notification
            || $entity instanceof Product
            || $entity instanceof User
        ) {
            $entity->setCreatedAt(new \DateTimeImmutable());
            if (method_exists($entity, 'setUpdatedAt')) {
                $entity->setUpdatedAt(new \DateTimeImmutable());
            }
        }
    }
}
