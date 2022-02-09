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

    public function selectAll()
    {
        $qb = $this->createQueryBuilder('n');

        $qb->innerJoin("n.state", "s")
            ->innerJoin("n.category", "cat")
            ->innerJoin("n.places", "pl")
            ->innerJoin("n.campus", "cam")
            ->innerJoin("n.participants", "p")
            ->innerJoin("n.organizer", "or")
            ->innerJoin("pl.city", "ci");

        return $qb->getQuery()->getResult();
    }

    public function selectFilter($campusName, $nightOutName, $startDate,$endDate)
    {
        $qb = $this->createQueryBuilder("n");

        $qb->innerJoin("n.state", "s")
            ->innerJoin("n.category", "cat")
            ->innerJoin("n.places", "pl")
            ->innerJoin("n.campus", "cam")
            ->innerJoin("n.participants", "p")
            ->innerJoin("n.organizer", "or")
            ->innerJoin("pl.city", "ci")
            ->where('cam.name = :name')
            ->andWhere('n.name LIKE :nightOutName')
            ->andWhere('n.startingTime > :startingTime')
            ->andWhere('n.dueDateInscription < :endTime')
            ->setParameter("name", $campusName)
            ->setParameter("nightOutName", $nightOutName)
            ->setParameter("startingTime", $startDate)
            ->setParameter("endTime", $endDate)
            ;
        $result = $qb->getQuery();
//        return $paginator = new Paginator($result);
        return $result->getResult();

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
