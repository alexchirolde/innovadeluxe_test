<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    // /**
    //  * @return Messages[] Returns an array of Messages objects
    //  */
    public function findByConversationId($value, $offset)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.conversation = :val')
            ->setParameter('val', $value)
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

}
