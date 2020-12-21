<?php

namespace App\Repository;

use App\Entity\Kclusters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Kclusters|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kclusters|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kclusters[]    findAll()
 * @method Kclusters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KclustersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kclusters::class);
    }

    // /**
    //  * @return Kclusters[] Returns an array of Kclusters objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kclusters
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
