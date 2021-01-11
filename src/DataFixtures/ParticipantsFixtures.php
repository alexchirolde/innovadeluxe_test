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
        $testingParticipant = new Participant();
        $testingParticipant->setName('Admin');
        $testingParticipant->setAvatar("https://fakeimg.pl/40x40/");
        $testingParticipant->setDateAdd(new \DateTime('now'));
        $manager->persist($testingParticipant);

        for ($i = 2; $i < 20; $i++) {
            $participant = new Participant();
            $participant->setName($this->faker->name);
            $participant->setAvatar("https://fakeimg.pl/40x40/");
            $participant->setDateAdd($this->faker->dateTimeBetween('-5 months', 'now'));
            $manager->persist($participant);

        }
        $manager->flush();
    }
}