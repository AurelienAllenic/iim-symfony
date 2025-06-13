<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'email'     => 'prof@gmail.com',
                'password'  => 'prof',
                'roles'     => ['ROLE_ADMIN'],
                'lastName'  => 'GILLES',
                'firstName' => 'Mathias',
                'points'    => 2500,
                'isActive'  => true,
            ],
            [
                'email'     => 'aurel@gmail.com',
                'password'  => 'aurel',
                'roles'     => ['ROLE_USER'],
                'lastName'  => 'ALLENIC',
                'firstName' => 'Aurélien',
                'points'    => 10500,
                'isActive'  => true,
            ],
            [
                'email'     => 'user@gmail.com',
                'password'  => 'user',
                'roles'     => ['ROLE_USER'],
                'lastName'  => 'USER',
                'firstName' => 'user',
                'points'    => 4500,
                'isActive'  => false,
            ],
            [
                'email'     => 'admin2@gmail.com',
                'password'  => 'admin2',
                'roles'     => ['ROLE_ADMIN'],
                'lastName'  => 'ADMIN2',
                'firstName' => 'admin2',
                'points'    => 75000,
                'isActive'  => false,
            ],
        ];

        foreach($usersData as $k => $v) {
            $user = new User();
            $user->setEmail($v['email']);
            $user->setRoles($v['roles']);
            $user->setPassword($this->hasher->hashPassword($user, $v['password']));
            $user->setLastName($v['lastName']);
            $user->setFirstName($v['firstName']);
            $user->setPoints($v['points']);
            $user->setIsActive($v['isActive']);

            $manager->persist($user);
            $this->addReference('user-'.$k, $user);
        }

        $manager->flush();
    }
}
