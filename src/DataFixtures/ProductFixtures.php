<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        $faker = Faker\Factory::create('fr_FR');

        $categories = [];

        for ($i = 0; $i < 5; $i++) {

            $categories[$i] = new Category();
            $categories[$i]->setName($faker->word);

            $manager->persist($categories[$i]);
        }

        // create 20 products! Bam!
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName($faker->name);
            $product->setSlug($faker->slug);
            $product->setDescription($faker->realText(150));
            $product->setSubtitle($faker->title);
            $product->setPrice(mt_rand(10, 100));
            $product->setIllustration('bonnet1.jpg');
            $product->setCategory($categories[mt_rand(0, count($categories)-1)]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}

  ?>