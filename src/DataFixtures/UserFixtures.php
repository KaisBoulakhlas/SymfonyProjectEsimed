<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use \App\Entity\AdminUser;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $category = new Category();
        $category->setName("Cat i");

        for($i = 0;$i < 10 ; $i++){

            $admin = new AdminUser();
            $admin->setEmail($faker->email);
            $admin->setUsername($faker->userName);
            $admin->setPlainPassword('test');

            for($j = 0; $j < 1; $j++){
                $advert = new Advert();
                $advert->setTitle($faker->title);
                $advert->setEmail($faker->email);
                $advert->setAuthor($faker->name);
                $advert->setCategory($category);
                $advert->setState('draft');
                $advert->setContent($faker->text);
                $advert->setPrice(100000.0);

                $picture = new Picture();
                $picture->setPath('file'.$j.'png');
                $picture->setAdvert($advert);

                $manager->persist($advert);
                $manager->persist($picture);
            }
             $manager->persist($admin);
        }


        $manager->flush();

    }
}
