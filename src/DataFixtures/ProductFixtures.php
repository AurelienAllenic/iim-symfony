<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $productsData = [
            [
                'name'          => 'stylo',
                'price'         => 200,
                'category'      => 'bureau',
                'description'   => 'un simple stylo',
                'user'          => $this->getReference('user-0', User::class),
            ],
            [
                'name'          => 'chaise',
                'price'         => 5000,
                'category'      => 'bureau',
                'description'   => 'une chaise gaming',
                'user'          => null,
            ],
            [
                'name'          => 'fourchette',
                'price'         => 150,
                'category'      => 'cuisine',
                'description'   => null,
                'user'          => $this->getReference('user-1', User::class),
            ],
            [
                'name'          => 'huile de tournesol',
                'price'         => 450,
                'category'      => 'cuisine',
                'description'   => null,
                'user'          => null,
            ],
            [
                'name'          => 'ballon',
                'price'         => 3000,
                'category'      => 'sport',
                'description'   => null,
                'user'          => null,
            ],
            [
                'name'          => 'chronometre',
                'price'         => 2500,
                'category'      => 'sport',
                'description'   => null,
                'user'          => null,
            ],
        ];

        foreach($productsData as $k => $v) {
            $product = new Product();
            $product->setName($v['name']);
            $product->setPrice($v['price']);
            $product->setCategory($v['category']);
            $product->setDescription($v['description']);
            $product->setUser($v['user']);

            $manager->persist($product);
            $this->addReference('product-'.$k, $product);
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