<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Messages::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Messages[] Returns an array of Messages objects
    //  */
    public function findByConversationId($value, $offset)
    {
        return $this->createQueryBuilder('m')
            ->select('m.dateAdd')
            ->andWhere('m.conversation = :val')
            ->setParameter('val', $value)
            ->leftJoin('m.messageFrom', 'mf')
            ->addSelect(
                'mf.name as messageFrom',
                'mf.avatar',
                'mf.id as messageFromId',
                'm.dateAdd as messageFromDateAdd',
                'm.messageText as messageFromText')
            ->leftJoin('m.messageTo', 'mt')
            ->addSelect(
                'mt.name as messageTo',
                'mt.id as messageToId',
                'm.dateAdd as messageToDateAdd',
                'm.messageText as messageToText')
            ->orderBy('m.dateAdd', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults(5 + $offset)
            ->getQuery()
            ->getResult();
    }

//    public function findOneByConversationId($value): ?Messages
//    {
//        return $this->createQueryBuilder('m')
//            ->innerJoin('m.conversation', 'c')
//            ->where('c.id = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function saveMessage($messageText, $dateAdd, $conversation, $messageFrom, $messageTo)
    {
        $newMessage = new Messages();

        $newMessage
            ->setMessageText($messageText)
            ->setConversation($conversation)
            ->setDateAdd($dateAdd)
            ->setMessageFrom($messageFrom)
            ->setMessageTo($messageTo);

        $this->manager->persist($newMessage);
        $this->manager->flush();
    }

}
