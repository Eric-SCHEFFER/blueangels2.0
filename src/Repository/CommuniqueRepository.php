<?php

namespace App\Repository;

use App\Entity\Communique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Communique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Communique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Communique[]    findAll()
 * @method Communique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommuniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Communique::class);
    }

    // /**
    //  * @return Communique[] Returns an array of Communique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Communique
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
