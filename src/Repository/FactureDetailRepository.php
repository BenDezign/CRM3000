<?php

namespace App\Repository;

use App\Entity\FactureDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FactureDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureDetail[]    findAll()
 * @method FactureDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureDetail::class);
    }

    // /**
    //  * @return FactureDetail[] Returns an array of FactureDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FactureDetail
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
