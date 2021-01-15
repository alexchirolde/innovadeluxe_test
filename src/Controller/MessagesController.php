<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Messages;
use App\Entity\Participant;
use App\Repository\MessagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/messages")
 */
class MessagesController extends AbstractController
{
    private $messageRepository;

    public function __construct(MessagesRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @Route("/new", name="messages_new", methods={"POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return JsonResponse
     */
    public function new(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $data = $request->request->all();
        $conversation = $em->getRepository(Conversation::class)->findOneBy(['id' => $data['conversationId']]);
        $messageFrom = $em->getRepository(Participant::class)->findOneBy(['id' => $data['messageFrom']]);
        $messageTo = $em->getRepository(Participant::class)->findOneBy(['id' => $data['messageTo']]);
        $messageText = $data['messageText'];
        $dateAdd = new \DateTime('now');

        $this->messageRepository->saveMessage($messageText, $dateAdd, $conversation, $messageFrom, $messageTo);

        return new JsonResponse(['status' => 'Message sent!'], Response::HTTP_CREATED);


    }


}
