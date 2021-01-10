<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class BaseFixtures
 * @package App\DataFixtures
 */
class ParticipantsFixtures extends BaseFixtures
{

    public function loadData(ObjectManager $manager)
    {

        for ($i = 0; $i < 10; $i++) {
            $participant = new Participant();
            $participant->setName($this->faker->name);
            $participant->setAvatar($this->faker->imageUrl(50, 50));
            $participant->setDateAdd($this->faker->dateTimeBetween('-5 months', 'now'));
            $manager->persist($participant);

        }
        $manager->flush();
    }
}