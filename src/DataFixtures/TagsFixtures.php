<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class BaseFixtures
 * @package App\DataFixtures
 */
class TagsFixtures extends BaseFixtures
{

    public function loadData(ObjectManager $manager){

        for ($i = 0; $i < 10; $i++){
            $tag = new Tags();
            $tag->setName($this->faker->word());
            $tag->setDateAdd($this->faker->dateTimeBetween('-5 months', 'now'));
            
            $manager->persist($tag);
        }
        $manager->flush();
    }


}