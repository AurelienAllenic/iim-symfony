<?php

namespace App\DataFixtures;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $notificationsData = [
            [
                'label' => '[creation] produit: fourchette',
                'user'  => $this->getReference('user-0', User::class),
            ],
            [
                'label' => '[modification] produit: fourchette',
                'user'  => $this->getReference('user-0', User::class),
            ],
            [
                'label' => '[achat] produit: fourchette',
                'user'  => $this->getReference('user-1', User::class),
            ],
        ];

        foreach($notificationsData as $k => $v) {
            $notification = new Notification();
            $notification->setLabel($v['label']);
            $notification->setUser($v['user']);

            $manager->persist($notification);
            $this->addReference('notification-'.$k, $notification);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}