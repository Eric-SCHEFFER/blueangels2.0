<?php

namespace App\Repository;

use App\Entity\ImagesCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImagesCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesCategories[]    findAll()
 * @method ImagesCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesCategories::class);
    }

    // /**
    //  * @return ImagesCategories[] Returns an array of ImagesCategories objects
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
    public function findOneBySomeField($value): ?ImagesCategories
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
