<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    // ==================== FUTURS =======================

    // On recupère jusqu'à 3 events futurs actifs
    // public function findActifEventsToCome($date)
    // {
    //     $query = $this->createQueryBuilder('e')
    //         ->select('e')
    //         ->andWhere('e.date_event >= :date')
    //         ->setParameter('date', $date->format('Y-m-d'))
    //         ->andWhere('e.actif = :actif')
    //         ->setParameter('actif', true)
    //         ->orderBy('e.date_event', 'ASC')
    //         ->setMaxResults(3)
    //         ->getQuery()
    //         ->getResult();
    //     return $query;
    // }

    // On recupère jusqu'à 3 events futurs actifs épinglés
    public function find3ActifPinnedEventsToCome($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_event >= :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->andWhere('e.actif = :actif')
            ->setParameter('actif', true)
            ->andWhere('e.epingle = :epingle')
            ->setParameter('epingle', true)
            ->orderBy('e.date_event', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On récupère jusqu'à 3 events futurs non épinglés
    public function find3ActifNonPinnedEventsToCome($date, $combien)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_event >= :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->andWhere('e.actif = :actif')
            ->setParameter('actif', true)
            ->andWhere('e.epingle = :epingle')
            ->setParameter('epingle', false)
            ->orderBy('e.date_event', 'ASC')
            ->setMaxResults($combien)
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On recupère tous les events futurs
    public function findAllEventsToCome($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_event >= :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('e.date_event', 'ASC')
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On recupère tous les events futurs actifs
    public function findAllActifEventsToCome($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_event >= :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->andWhere('e.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('e.date_event', 'ASC')
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On compte tous les events futurs
    public function countTotalEventsToCome($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.date_event >= :date')
            ->setParameter('date', $date->format('Y-m-d')) // Ne compare que l'année, mois, jour pour que le jour de l'event soit valide jusqu'à 23:59:59
            ->select('count(e.id)')
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On compte tous les events futurs actifs
    public function countTotalActifEventsToCome($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.date_event >= :date')
            ->setParameter('date', $date->format('Y-m-d')) // Ne compare que l'année, mois, jour pour que le jour de l'event soit valide jusqu'à 23:59:59
            ->andWhere('e.actif = :actif')
            ->setParameter('actif', true)
            ->select('count(e.id)')
            ->getQuery()
            ->getResult();
        return $query;
    }




    // ==================== PASSÉS =======================

    // On recupère tous les events passés
    public function findAllCompletedEvents($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.date_event < :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('e.date_event', 'DESC')
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On recupère tous les events passés actifs
    public function findAllActifCompletedEvents($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_event < :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->andWhere('e.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('e.date_event', 'DESC')
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On compte tous les events passés
    public function countTotalCompletedEvents($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_event < :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->select('count(e.id)')
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On compte tous les events passés actifs
    public function countTotalActifCompletedEvents($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_event < :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->andWhere('e.actif = :actif')
            ->setParameter('actif', true)
            ->select('count(e.id)')
            ->getQuery()
            ->getResult();
        return $query;
    }










    // On récupère un nombre d'events passés, dépendant des events futurs trouvés dans la méthode du haut
    // public function findCompletedEvents($date, $combien)
    // {
    //     $query = $this->createQueryBuilder('e')
    //         ->select('e')
    //         ->where('e.date_event < :date')
    //         ->setParameter('date', $date->format('Y-m-d'))
    //         ->orderBy('e.date_event', 'DESC')
    //         ->setMaxResults($combien)
    //         ->getQuery()
    //         ->getResult();
    //     return $query;
    // }



    // /**
    //  * @return Events[] Returns an array of Events objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Events
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
