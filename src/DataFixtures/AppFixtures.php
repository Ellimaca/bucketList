<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $category = new Category();
        $category->setName('Sport');
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName('Travel & Adventure');
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Human Relations');
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setName('Others');
        $manager->persist($category4);

        $category5 = new Category();
        $category5->setName('Entertainment');
        $manager->persist($category5);

        $manager->flush();

    }
}
