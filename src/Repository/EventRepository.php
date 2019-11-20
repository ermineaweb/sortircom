<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    /**
    //  * @return Event[] Returns an array of Event objects
    //  */

    public function findByFilters($search = null, $start = null, $end = null, $school = null)
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->addSelect('s');

        $queryBuilder->orderBy('e.start', 'ASC');
        if ($school !=null){

        }
            if ($search !=null) {
                $queryBuilder->andWhere('e.name like :val');
                $queryBuilder->setParameter('val', '%'.$search.'%');
            }
            if ($start !=null && $end !=null) {
                $queryBuilder->andWhere('e.start BETWEEN :start AND :end');
                $queryBuilder->setParameter('start', \DateTime::createFromFormat('Y-m-d', $start));
                $queryBuilder->setParameter('end', \DateTime::createFromFormat('Y-m-d', $end));
            }
        $queryBuilder->setMaxResults(20);
        $query = $queryBuilder->getQuery();
        dump($query);
        return $query->getResult();
    }


    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
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
