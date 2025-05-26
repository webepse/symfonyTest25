<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 0; $i < 10; $i++){
            $product = new Product();
            $product->setName($faker->name())
            ->setDescription($faker->text())
            ->setPrice($faker->randomFloat(2, 10, 100))
            ->setDate($faker->dateTimeBetween('now', '+1 year'));

            $manager->persist($product);

        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
