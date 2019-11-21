<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{

    const PAGINATION = 10;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    /**
    //  * @return Event[] Returns an array of Event objects
    //  */
// PAR DEFAUT LA SCHOOL EST LA SCHOOL DE USER CONNECTE
    public function findByFilters($search = null, $start = null, $end = null, $school = null, $page = 1) : Paginator
    {
        $queryBuilder = $this->createQueryBuilder('event');
        //$queryBuilder->addSelect('s');
        /*$queryBuilder->innerJoin('event.creator', 'creator');
        $queryBuilder->innerJoin('creator.school', 'sch');
        $queryBuilder->orderBy('event.start', 'ASC');
        if ($school !=null){
            $queryBuilder->andWhere('sch.name =:school');
            $queryBuilder->setParameter('school', $school);
        }*/
            if ($search !=null) {
                $queryBuilder->andWhere('event.name like :val');
                $queryBuilder->setParameter('val', '%'.$search.'%');
            }
            if ($start !=null && $end !=null) {
                $queryBuilder->andWhere('event.start BETWEEN :start AND :end');
                $queryBuilder->setParameter('start', \DateTime::createFromFormat('Y-m-d', $start));
                $queryBuilder->setParameter('end', \DateTime::createFromFormat('Y-m-d', $end));
            }
        $queryBuilder->setFirstResult(($page-1)*EventRepository::PAGINATION);
            $queryBuilder->setMaxResults(EventRepository::PAGINATION);
        $query = $queryBuilder->getQuery();
        dump($query);
        return new Paginator($query, $fetchJoinCollection = true);
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
