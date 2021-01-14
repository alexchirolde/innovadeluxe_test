<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Messages;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ConversationController extends AbstractController
{
    /**
     * @Route("/conversation", name="conversation")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        $conversationsParticipants = $this->getConversationsOfParticipant($em);
//        dd($conversationsParticipants);

        return $this->render('conversation/index.html.twig', [
            'conversations' => $conversationsParticipants,
            'currentParticipant' => 1
        ]);
    }

    /**
     * @Route ("/ajaxConversations/{id}/{offset}")
     * @param EntityManagerInterface $em
     * @param $id
     * @param $offset
     * @return string
     */
    public function getConversation(EntityManagerInterface $em, $id, $offset)
    {
        $encoders = [new JsonEncoder()];
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizers = [new ObjectNormalizer(
            null,
            null,
            null,
            null,
            null,
            null,
            $defaultContext)];
        $serializer = new Serializer($normalizers, $encoders);
        $messages = $em->getRepository(Messages::class)->findByConversationId($id, $offset);
        $messages[count($messages)]['currentUser'] = 1;

        return new JsonResponse($serializer->serialize($messages, 'json'));
    }

    public function getConversationsOfParticipant($em, $limit = 10)
    {
        $arr = array();
        $testParticipant = $em->getRepository(Participant::class)->findOneBy(['name' => 'Admin']);
        $conversations = $testParticipant->getConversation();
        foreach ($conversations as $conversation) {
            $arr[$conversation->getId()] = $conversation->getParticipant();
        }

        return array_slice($arr, 0, $limit, true);
    }
}
