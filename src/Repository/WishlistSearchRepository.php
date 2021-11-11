<?php

namespace App\Repository;

use App\Entity\WishlistSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WishlistSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method WishlistSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method WishlistSearch[]    findAll()
 * @method WishlistSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishlistSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishlistSearch::class);
    }

    // /**
    //  * @return WishlistSearch[] Returns an array of WishlistSearch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WishlistSearch
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
