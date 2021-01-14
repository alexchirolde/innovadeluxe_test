<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use App\Repository\MessagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/messages")
 */
class MessagesController extends AbstractController
{

    /**
     * @Route("/new", name="messages_new", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $message = new Messages();
        $message->setConversation($request['conversationId']);
        $message->setMessageFrom($request['messageFrom']);
        $message->setMessageTo($request['messageTo']);
        $message->setMessageText($request['messageText']);
        $message->setDateAdd(new \DateTime('now'));


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($message);
        $entityManager->flush();

//        return

    }


}
