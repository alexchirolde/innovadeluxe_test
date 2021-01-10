<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Entity\Messages;
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
class MessagesFixtures extends BaseFixtures implements DependentFixtureInterface
{

    public function loadData(ObjectManager $manager){

        $conversations = $manager->getRepository(Conversation::class)->findAll();
        $participants = $manager->getRepository(Participant::class)->findAll();
        $tags = $manager->getRepository(Tags::class)->findAll();

        foreach ($conversations as $conversation){
            $participantFrom = $participants[rand(0, count($participants) - 1)];
            $participantTo = $participants[rand(0, count($participants) - 1)];
            if ($participantTo == $participantFrom)
                $participantTo = $participants[rand(0, count($participants) - 1)];
            for ($i = 0; $i < rand(10, 20); $i++){
                $message = new Messages();
                $message->setConversation($conversation);
                $message->setMessageFrom($participantFrom);
                $message->setMessageTo($participantTo);
                $message->setMessageText($this->faker->text());
                $message->setDateAdd($this->faker->dateTimeBetween('-5 months', 'now'));

                $manager->persist($message);
            }
            $conversation->addTag($tags[rand(0, count($tags) - 1)]);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ConversationFixtures::class,
            ParticipantsFixtures::class,
            TagsFixtures::class,
        );
    }

}