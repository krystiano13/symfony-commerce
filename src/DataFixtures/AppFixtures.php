<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private array $products = [
        [
            'name' => 'Intel Core i5-12500',
            'amount' => 5,
            'price' => 779.99,
            'imageSrc' => 'https://cdn.x-kom.pl/i/setup/images/prod/big/product-new-big,,2021/12/pr_2021_12_23_9_30_34_58_00.jpg',
        ],
        [
            'name' => 'AMD Ryzen 5 5600',
            'amount' => 5,
            'price' => 439.99,
            'imageSrc' => 'https://cdn.x-kom.pl/i/setup/images/prod/big/product-new-big,,2022/4/pr_2022_4_4_13_1_27_284_00.jpg',
        ],
        [
            'name' => 'Intel Core i5-14400f',
            'amount' => 5,
            'price' => 679.99,
            'imageSrc' => 'https://cdn.x-kom.pl/i/setup/images/prod/big/product-new-big,,2024/1/pr_2024_1_5_10_17_14_409_00.jpg',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->products as $pr) {
            $product = new Product();
            $product->setName($pr['name']);
            $product->setPrice($pr['price']);
            $product->setAmount($pr['amount']);
            $product->setImageSrc($pr['imageSrc']);

            $manager->persist($product);

            $manager->flush();
        }

        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setPassword('$2a$10$/o7.iXDf4gox3KZlE2v6AezklVKbVtzersuIpvqkpLjuVigFk3Aau');
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $manager->flush();
    }
}
