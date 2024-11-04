<?php

namespace App\Repository;

use App\Entity\Repas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Repas>
 */
class RepasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repas::class);
    }

    public function findByCategories(array $categories): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.categorie IN (:categories)')
            ->setParameter('categories', $categories)
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByIngredientsAndCategories($ingredientId, array $categories)
    {
        return $this->createQueryBuilder('r')
            ->join('r.ingredientQuantites', 'iq')
            ->join('iq.ingredient', 'i')
            ->where('i.id = :ingredientId')
            ->andWhere('r.categorie IN (:categories)')
            ->setParameter('ingredientId', $ingredientId)
            ->setParameter('categories', $categories)
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByIngredient($ingredientId)
    {
        return $this->createQueryBuilder('r')
            ->join('r.ingredientQuantites', 'iq')
            ->join('iq.ingredient', 'i')
            ->where('i.id = :ingredientId')
            ->setParameter('ingredientId', $ingredientId)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Repas[] Returns an array of Repas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Repas
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
