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
     * On recupère les articles épinglés, actifs et listés. (Si on récupère tous les articles, $combien doit avoir la valuer null).
     */
    public function findPinnedActifsListedArticles($combien)
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :val')
            ->setParameter('val', true)
            ->andwhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->andwhere('a.listed = :listed')
            ->setParameter('listed', true)
            ->orderBy('a.created_at', 'DESC')
            ->setMaxResults($combien)
            ->getQuery()
            ->getResult();
        return $query;
    }

    /**
     * On recupère les articles actifs et listés
     */
    public function findActifsListedArticles($combien)
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :val')
            ->setParameter('val', false)
            ->andwhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->andwhere('a.listed = :listed')
            ->setParameter('listed', true)
            ->orderBy('a.created_at', 'DESC')
            ->setMaxResults($combien)
            ->getQuery()
            ->getResult();
        return $query;
    }



    // ----- PAR CATÉGORIE -----

    /**
     * On recupère les articles épinglés, actifs et listés, par catégorie. (Si on récupère tous les articles, $combien doit avoir la valuer null).
     */
    public function findPinnedActifsListedArticlesByCategorie($idCategorie, $combien)
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :val')
            ->setParameter('val', true)
            ->andwhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->andwhere('a.categories_article = :cat')
            ->setParameter('cat', $idCategorie)
            ->orderBy('a.created_at', 'DESC')
            ->setMaxResults($combien)
            ->getQuery()
            ->getResult();
        return $query;
    }


    /**
     * On recupère les articles actifs et listés, par catégorie.
     */
    public function findActifsListedArticlesByCategorie($idCategorie, $combien)
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->andwhere('a.epingle = :val')
            ->setParameter('val', false)
            ->andwhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->andwhere('a.categories_article = :cat')
            ->setParameter('cat', $idCategorie)
            ->orderBy('a.created_at', 'DESC')
            ->setMaxResults($combien)
            ->getQuery()
            ->getResult();
        return $query;
    }



    // ================== ADMIN ========================

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
