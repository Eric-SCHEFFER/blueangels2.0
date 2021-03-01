<?php

namespace App\Repository;

use App\Entity\CategoriesImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriesImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriesImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriesImage[]    findAll()
 * @method CategoriesImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriesImage::class);
    }

    // /**
    //  * @return CategoriesImage[] Returns an array of CategoriesImage objects
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
    public function findOneBySomeField($value): ?CategoriesImage
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
