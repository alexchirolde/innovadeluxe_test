<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Entity\Messages;
use App\Entity\Participant;
use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class BaseFixtures
 * @package App\DataFixtures
 */
class ConversationFixtures extends BaseFixtures
{

    public function loadData(ObjectManager $manager){

        for ($i = 0; $i < 10; $i++){
            $conversation = new Conversation();
            $conversation->setDateAdd($this->faker->dateTimeBetween('-5 months', 'now'));
            $conversation->setDateUpdated($this->faker->dateTime('now'));

            $manager->persist($conversation);
        }
        $manager->flush();
    }

}