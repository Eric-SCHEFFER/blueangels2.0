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


    // On recupère jusqu'à 3 events futurs
    public function findEventsToCome($date)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.date_event >= :date')
            ->setParameter('date', $date)
            ->orderBy('e.date_event', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
        return $query;
    }

    // On récupère un nombre d'events passés, dépendant des events futurs trouvés dans la méthode du haut
    public function findCompletedEvents($date, $combien)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.date_event < :date')
            ->setParameter('date', $date)
            ->orderBy('e.date_event', 'DESC')
            ->setMaxResults($combien)
            ->getQuery()
            ->getResult();
        return $query;
    }



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
