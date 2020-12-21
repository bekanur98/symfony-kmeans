<?php

namespace App\Repository;

use App\Entity\Workers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Workers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workers[]    findAll()
 * @method Workers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workers::class);
    }

    // /**
    //  * @return Workers[] Returns an array of Workers objects
    //  */

    public function getWorkersByPage($page = 1, $order = "id")
    {

        $em = $this->getEntityManager();

        $workers = $em->getRepository(Workers::class);

        $orderBy = 'u.'.$order;

        $query = $workers->createQueryBuilder('u')
            ->orderBy($orderBy, 'ASC')
            ->getQuery();

        $pageSize = '100';

        $paginator = new Paginator($query);

        $totalItems = sizeof($paginator);

        $pageCount = ceil($totalItems / $pageSize);

        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page-1))
            ->setMaxResults($pageSize);


        return [$paginator, $totalItems, $pageCount];
    }


    public function getClustering($clusterCount){
        $procedureQuery = $this->createNativeNamedQuery('CALL kmeans()');
        dd($procedureQuery->getResult());
    }

    /*
    public function findOneBySomeField($value): ?Workers
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
