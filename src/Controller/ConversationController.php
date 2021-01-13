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
        $participants = array();
        $testParticipant = $em->getRepository(Participant::class)->findOneBy(['name' => 'Admin']);
        $conversations = $testParticipant->getConversation();
        foreach ($conversations as $conversation) {
            $participants[$conversation->getId()] = $conversation->getParticipant();
        }
        return $this->render('conversation/index.html.twig', [
            'participants' => $participants,
            'currentParticipant' => $testParticipant->getid()
        ]);
    }

    /**
     * @Route ("/ajaxConversations/{id}/{offset}")
     * @param EntityManagerInterface $em
     * @param $id
     * @param $offset
     * @return string
     */
    public function getConversation(EntityManagerInterface $em, $id, $offset = 0)
    {
        $testParticipant = $em->getRepository(Participant::class)->findOneBy(['name' => 'Admin']);
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
        $messages[count($messages)]['currentUser'] = $testParticipant->getId();

        return new JsonResponse($serializer->serialize($messages, 'json'));
    }
}
