<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    public function findById($value, $offset)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.participant', 'p')
            ->select('p.id')
            ->where('p.id = :val')
            ->setParameter('val', $value)
            ->innerJoin('c.participant', 'otherParticipant')
            ->select('otherParticipant.name', 'otherParticipant.avatar', 'c.id as conversationId')
            ->andWhere('otherParticipant.id != p.id')
            ->orderBy('c.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults(5 + $offset)
            ->getQuery()
            ->getResult();

    }

    /*
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
