<?php

namespace App\Repository;

use App\Entity\Campus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Campus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campus[]    findAll()
 * @method Campus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }

    public function findCampus($user){
        $qb = $this->createQueryBuilder('c');
        $qb->where(':user MEMBER OF c.participants')
            ->setParameter('user', $user)
            ->join('c.participants', 'p')
            ->addSelect('p');
        $query = $qb->getQuery();
        return $query->getResult();

    }

    public function findCampuss($filtre=null)
    {
        $qb = $this->createQueryBuilder('c');
        if ($filtre != null) {
            $qb->andWhere('c.nomCampus LIKE :nom')
                ->setParameter('nom', '%' . $filtre . '%');
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }

    // /**
    //  * @return Campus[] Returns an array of Campus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Campus
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
