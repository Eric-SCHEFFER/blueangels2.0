<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }


    /**
     * On recupère au maximum 3 articles epinglés actifs
     */
    public function findPinnedActifsArticles()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :val')
            ->setParameter('val', true)
            ->andwhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('a.created_at', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
        return $query;
    }


    /**
     * On recupère tous les articles epinglés actifs
     */
    public function findAllPinnedActifsArticles()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :epingle')
            ->setParameter('epingle', true)
            ->andwhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult();
        return $query;
    }


    /**
     * On récupère tous les articles non-epinglés actifs
     */
    public function findAllActifsArticles()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :epingle')
            ->setParameter('epingle', false)
            ->andwhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult();
        return $query;
    }


    /**
     * On récupère "$combien" d'articles non-epinglés actifs
     */
    public function findActifsArticles($combien)
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :val')
            ->setParameter('val', false)
            ->andwhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('a.created_at', 'DESC')
            ->setMaxResults($combien)
            ->getQuery()
            ->getResult();
        return $query;
    }



    // ================== Partie admin ========================

    /**
     * On recupère tous les articles epinglés
     */
    public function findAllPinnedArticles()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :val')
            ->setParameter('val', true)
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult();
        return $query;
    }


    /**
     * On récupère tous les articles non-epinglés
     */
    public function findAllArticles()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :epingle')
            ->setParameter('epingle', false)
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult();
        return $query;
    }







    // /**
    //  * @return Articles[] Returns an array of Articles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
        ->andWhere('a.exampleField = :val')
        ->setParameter('val', $value)
        ->orderBy('a.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('a')
        ->andWhere('a.exampleField = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
    */
}
