<?php

namespace App\Repository;

use App\Entity\OptionsSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OptionsSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionsSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionsSite[]    findAll()
 * @method OptionsSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionsSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionsSite::class);
    }

    // /**
    //  * @return OptionsSite[] Returns an array of OptionsSite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OptionsSite
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
