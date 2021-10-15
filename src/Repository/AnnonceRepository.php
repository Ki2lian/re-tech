<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
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
    public function findOneBySomeField($value): ?Annonce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * 
     */
    public function annonceByTag($listId){
        $sizeList = sizeof($listId);
        $listId = implode(',', $listId);
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $sql = 'SELECT annonce.id, annonce.titre, annonce.description, annonce.prix, annonce.annonce_payante, annonce.id_compte_id, annonce.date_creation, annonce.date_modification
                FROM annonce
                JOIN ( SELECT annonce_id, COUNT(annonce_id) as cnt FROM `annonce_tag` WHERE tag_id IN ('.$listId.') GROUP BY annonce_id ) temp 
                ON temp.annonce_id = annonce.id
                WHERE temp.cnt >= '.$sizeList.' AND annonce.actif = 1
        ';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
        return $stmt->fetchAll();

        // $query = $this->getEntityManager()->createQuery("
        //     SELECT annonce_id, COUNT(annonce_id) as cnt FROM annonce_tag WHERE tag_id IN (1,2) GROUP BY annonce_id 
        // ");
        
        // // SELECT annonce_id FROM ( ) test WHERE test.cnt >= 2
        // return $query->getResult();

    }
}
