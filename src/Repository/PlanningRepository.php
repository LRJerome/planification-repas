<?php

namespace App\Repository;

use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use DateTimeInterface;

/**
 * @extends ServiceEntityRepository<Planning>
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    /**
     * Trouve tous les plannings d'une semaine donnée
     */
    public function findByWeek(DateTimeInterface $date): array
    {
        $dateTime = $date instanceof DateTime ? $date : new DateTime($date->format('Y-m-d'));
        $startOfWeek = (clone $dateTime)->modify('monday this week');
        $endOfWeek = (clone $startOfWeek)->modify('+6 days');

        return $this->createQueryBuilder('p')
            ->leftJoin('p.petitDejeuner', 'pd')
            ->leftJoin('p.encasMatin', 'em')
            ->leftJoin('p.dejeuner', 'd')
            ->leftJoin('p.encasApresMidi', 'ea')
            ->leftJoin('p.diner', 'di')
            ->addSelect('pd', 'em', 'd', 'ea', 'di')
            ->andWhere('p.date BETWEEN :start AND :end')
            ->setParameter('start', $startOfWeek->format('Y-m-d'))
            ->setParameter('end', $endOfWeek->format('Y-m-d'))
            ->orderBy('p.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve tous les plannings entre deux dates
     */
    public function findByDateRange(DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.petitDejeuner', 'pd')
            ->leftJoin('p.encasMatin', 'em')
            ->leftJoin('p.dejeuner', 'd')
            ->leftJoin('p.encasApresMidi', 'ea')
            ->leftJoin('p.diner', 'di')
            ->addSelect('pd', 'em', 'd', 'ea', 'di')
            ->andWhere('p.date >= :start')
            ->andWhere('p.date <= :end')
            ->setParameter('start', $startDate->format('Y-m-d'))
            ->setParameter('end', $endDate->format('Y-m-d'))
            ->orderBy('p.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve un planning pour une date spécifique
     */
    public function findOneByDate(DateTimeInterface $date): ?Planning
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.petitDejeuner', 'pd')
            ->leftJoin('p.encasMatin', 'em')
            ->leftJoin('p.dejeuner', 'd')
            ->leftJoin('p.encasApresMidi', 'ea')
            ->leftJoin('p.diner', 'di')
            ->addSelect('pd', 'em', 'd', 'ea', 'di')
            ->andWhere('p.date = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Sauvegarde un planning
     */
    public function save(Planning $planning, bool $flush = false): void
    {
        $this->getEntityManager()->persist($planning);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime un planning
     */
    public function remove(Planning $planning, bool $flush = false): void
    {
        $this->getEntityManager()->remove($planning);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByDateWithMeals(\DateTime $date): ?Planning
    {
        $startOfDay = clone $date;
        $startOfDay->setTime(0, 0, 0);
        
        $endOfDay = clone $date;
        $endOfDay->setTime(23, 59, 59);

        return $this->createQueryBuilder('p')
            ->leftJoin('p.petitDejeuner', 'pd')
            ->leftJoin('p.encasMatin', 'em')
            ->leftJoin('p.dejeuner', 'd')
            ->leftJoin('p.encasApresMidi', 'ea')
            ->leftJoin('p.diner', 'di')
            ->addSelect('pd', 'em', 'd', 'ea', 'di')
            ->andWhere('p.date = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByDateRangeWithMeals(\DateTime $start, \DateTime $end): array 
    {
        $startOfDay = clone $start;
        $startOfDay->setTime(0, 0, 0);
        
        $endOfDay = clone $end;
        $endOfDay->setTime(23, 59, 59);

        return $this->createQueryBuilder('p')
            ->leftJoin('p.petitDejeuner', 'pd')
            ->leftJoin('p.encasMatin', 'em')
            ->leftJoin('p.dejeuner', 'd')
            ->leftJoin('p.encasApresMidi', 'ea')
            ->leftJoin('p.diner', 'di')
            ->addSelect('pd', 'em', 'd', 'ea', 'di')
            ->andWhere('p.date >= :start')
            ->andWhere('p.date <= :end')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->orderBy('p.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}