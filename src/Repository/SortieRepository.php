<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findSortie($filtre = null, $contient = null, $debut = null, $fin = null, $organisateur = null, $inscrit = null, $pas_inscrit = null, $passe = null)
    {
        $qb = $this->createQueryBuilder('s');
        if ($filtre != null) {
            $qb->andWhere('s.campus IN (:campus)')
                ->setParameter('campus', $filtre);
        }
        if ($contient != null) {
            $qb->andWhere('s.nom LIKE :word')
                ->setParameter('word', '%' . $contient . '%');
        }
        if ($debut != null && $fin != null){
            $qb->andWhere('s.dateDebut >= :deb')
                ->setParameter('deb', $debut)
                ->andWhere('s.dateDebut <= :fin')
                ->setParameter('fin', $fin);
        }

        if($organisateur != null){
            $qb->andWhere('s.organisateur IN(:id)')
            ->setParameter('id', $organisateur);
        }

        if($inscrit != null){
            $qb->andWhere(':id_p MEMBER OF s.participants')
                ->setParameter('id_p', $inscrit);
        }

        if($pas_inscrit != null){
            $qb->andWhere(':id_pi NOT MEMBER OF s.participants')
                ->setParameter('id_pi', $pas_inscrit );
        }

        if($passe != null){
            $qb->andWhere('s.dateDebut < :now')
                ->setParameter('now', $passe);
        }

        $qb->join('s.etat', 'et')
            ->addSelect('et')
            ->join('s.organisateur', "o")
            ->addSelect('o')
            ->leftJoin('s.participants', "p")
            ->addSelect("p")
            ->orderBy('s.dateDebut');
        $query = $qb->getQuery();
        return $query->getResult();
    }


    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
