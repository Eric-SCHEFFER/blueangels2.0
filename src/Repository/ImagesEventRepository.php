<?php

namespace App\Repository;

use App\Entity\ImagesEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImagesEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesEvent[]    findAll()
 * @method ImagesEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesEvent::class);
    }

    // /**
    //  * @return ImagesEvent[] Returns an array of ImagesEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImagesEvent
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
