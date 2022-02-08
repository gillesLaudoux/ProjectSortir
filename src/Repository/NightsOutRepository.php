<?php

namespace App\Repository;

use App\Entity\NightsOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NightsOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method NightsOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method NightsOut[]    findAll()
 * @method NightsOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NightsOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NightsOut::class);
    }

    // /**
    //  * @return NightsOut[] Returns an array of NightsOut objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NightsOut
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
