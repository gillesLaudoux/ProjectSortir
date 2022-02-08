<?php

namespace App\Repository;

use App\Entity\NightOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NightOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method NightOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method NightOut[]    findAll()
 * @method NightOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NightOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NightOut::class);
    }

    // /**
    //  * @return NightOut[] Returns an array of NightOut objects
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
    public function findOneBySomeField($value): ?NightOut
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
