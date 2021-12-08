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
    public function annonceByTag($listNom, $skip, $fetch){
        $test = sizeof($listNom);
        // $listNom = implode(',', $listNom);
        return $this->createQueryBuilder('a')
            ->join('a.liste_id_tag', 't')
            ->where('t.nom in (:listNom)')
            ->setParameter('listNom', $listNom)
            // ->setParameter('listNom', "apple,telephone")
            ->orderBy('a.id', 'DESC')
            ->distinct()
            ->setMaxResults($fetch)
            ->setFirstResult($skip)
            ->getQuery()
            ->getResult()
        ;
        /*
        $sizeList = sizeof($listId);
        $listId = implode(',', $listId);
        
        $conn = new \PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_DBNAME'].";charset=UTF8", $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
        $sql = 'SELECT annonce.id, annonce.titre, annonce.description, annonce.prix, annonce.annonce_payante, 
        annonce.id_compte_id, annonce.date_creation, annonce.date_modification, image.id as imageId, image.nom as imageNom, image.presentation as imagePresentation
                FROM annonce
                JOIN ( SELECT annonce_id, COUNT(annonce_id) as cnt FROM `annonce_tag` WHERE tag_id IN ('.$listId.') GROUP BY annonce_id ) temp 
                ON temp.annonce_id = annonce.id
                LEFT JOIN image ON annonce.id=image.id_annonce_id
                WHERE temp.cnt >= '.$sizeList.' AND annonce.actif = 1
                LIMIT '.$skip.', '.$fetch.'
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);*/


    }

    public function countAllAnnonces(){
        return $this->createQueryBuilder('a')
        ->select('count(a.id)')
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function countAllAnnoncesByTag($listNom){
        $listNom = explode(",", $listNom);
        return $this->createQueryBuilder('a')
        ->select('count(a.id)')
        ->join('a.liste_id_tag', 't')
        ->where('t.nom in (:listNom)')
        ->setParameter('listNom', $listNom)
        ->distinct()
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function countAllAnnoncesPaid(){
        return $this->createQueryBuilder('a')
        ->select('count(a.id)')
        ->where('a.annonce_payante = 1')
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function countAllProductsPostedByMonth(){
        return $this->createQueryBuilder('a')
        ->select('MONTH(a.date_creation) AS MONTH, COUNT(a.id) AS COUNT')
        ->where('YEAR(a.date_creation)=:year')
        ->setParameter(':year', date("Y"))
        ->groupBy('MONTH')
        ->getQuery()
        ->getResult();
    }

    public function countAllSellers(){
        // $conn = new \PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_DBNAME'].";charset=UTF8", $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        // $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // $req = $conn->prepare(' SELECT COUNT(DISTINCT(id_compte_id))
        //                         FROM annonce');

        // $req->execute();
        // $test = $req->fetch()[0];
        // return $test;
        return $this->createQueryBuilder('a')
        ->select('count(DISTINCT u.id)')
        ->join('a.id_compte', 'u')
        ->getQuery()
        ->getSingleScalarResult();
    }
}
