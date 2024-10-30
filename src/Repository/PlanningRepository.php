<?php

namespace App\Repository;

use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    public function findByWeek(\DateTime $date): array
    {
        $startOfWeek = (clone $date)->modify('monday this week');
        $endOfWeek = (clone $startOfWeek)->modify('+6 days');

        $result = $this->createQueryBuilder('p')
            ->andWhere('p.date BETWEEN :start AND :end')
            ->setParameter('start', $startOfWeek)
            ->setParameter('end', $endOfWeek)
            ->orderBy('p.date', 'ASC')
            ->getQuery()
            ->getResult();


        return $result;
    }
    public function findByDateRange(\DateTime $start, \DateTime $end)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.date >= :start')
        ->andWhere('p.date <= :end')
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->orderBy('p.date', 'ASC')
        ->getQuery()
        ->getResult();
}
}