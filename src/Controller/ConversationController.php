<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route ("/ajaxConversations/{id}")
     * @param EntityManagerInterface $em
     * @param $id
     */
    public function getConversation(EntityManagerInterface $em, $id)
    {
    }
}
