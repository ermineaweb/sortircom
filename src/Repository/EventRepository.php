<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    const PAGINATION =1000;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    /**
    //  * @return Event[] Returns an array of Event objects
    //  */
// PAR DEFAUT LA SCHOOL EST LA SCHOOL DE USER CONNECTE
    public function findByFilters($search = null,
                                  $start = null,
                                  $end = null,
                                  $school = null,
                                  $page = 1,
                                  $pastevents = null,
                                  $eventscreated = null,
                                  $registered = null,
                                  $notregistered = null,
                                  $user = null,
                                  $userId = null) : Paginator
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->addSelect('u');
        $queryBuilder->leftJoin('e.users', 'u');
        //$queryBuilder->addSelect('sch');
       /* $queryBuilder->addSelect('sch');
        $queryBuilder->innerJoin('event.creator', 'creator');
        $queryBuilder->innerJoin('creator.school', 'sch');*/
        /*if ($school !=null){
            $queryBuilder->andWhere('school.name =:school');
            $queryBuilder->setParameter('school', $school);
        }*/
        $queryBuilder->andWhere("DATE_DIFF(CURRENT_DATE(), e.end) <= 31");
            if ($search !=null) {
                $queryBuilder->andWhere('e.name like :val');
                $queryBuilder->setParameter('val', '%'.$search.'%');
            }
            if ($start !=null && $end !=null) {
                $queryBuilder->andWhere('e.start BETWEEN :start AND :end');
                $queryBuilder->setParameter('start', \DateTime::createFromFormat('Y-m-d', $start));
                $queryBuilder->setParameter('end', \DateTime::createFromFormat('Y-m-d', $end));
            }
            if ($pastevents !=null) {
                $queryBuilder->andWhere("DATE_DIFF(CURRENT_DATE(), e.start) <= 31");
            } else {
				$queryBuilder->andWhere('DATE_DIFF(CURRENT_DATE(), e.limitdate) < 0');
			}
            if ($eventscreated !=null) {
                $queryBuilder->andWhere('e.creator =:user');
                $queryBuilder->setParameter('user', $user);
            }
            if ($registered !=null) {
                $queryBuilder->andWhere(':user MEMBER OF e.users' );
                $queryBuilder->setParameter('user', $user);
            }
            if ($notregistered !=null) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull('u.id'));
            }
		$queryBuilder->orderBy('e.status', 'ASC');
		$queryBuilder->orderBy('e.limitdate', 'ASC');
		
        $queryBuilder->setFirstResult(($page-1)*EventRepository::PAGINATION);
            $queryBuilder->setMaxResults(EventRepository::PAGINATION);
        $query = $queryBuilder->getQuery();
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
