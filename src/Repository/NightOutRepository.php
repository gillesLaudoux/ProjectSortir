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

    /** INNER JOIN sur toutes les tables pour récuperer toutes les infos liées à une sortie */
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

    /** INNER JOIN sur les tables necessaire à la filtration de la recherche */
    public function selectFilter($campusName, $nightOutName, $startDate, $endDate, $idOrganizer, $idParticipant,
                                 $idNotParticipant)
    {
        $qb = $this->createQueryBuilder("n");
        $qb->innerJoin("n.state", "s")
            ->innerJoin("n.category", "cat")
            ->innerJoin("n.places", "pl")
            ->innerJoin("n.campus", "cam")
            ->innerJoin("n.participants", "p")
            ->innerJoin("n.organizer", "org")
            ->innerJoin("pl.city", "ci")
            ->where("s.id = '2'")// affiche que les night_out ouvertes
            ->andWhere('n.name LIKE :nightOutName')
            ->andWhere('n.startingTime > :startingTime')
            ->andWhere('n.dueDateInscription < :endTime')
            ->setParameter("nightOutName", $nightOutName)
            ->setParameter("startingTime", $startDate)
            ->setParameter("endTime", $endDate);

        if(!is_null($campusName)){
            $qb->andWhere('cam.name = :name')
                ->setParameter("name", $campusName);
        }

        /* Si l'id d'un organizer est donné, on sélectionne que les NightOut qu'il organise */
        if(!is_null($idOrganizer)){
            dump("Repos add option idOrganizer");
            $qb->andWhere("org.id = :idOrganizer ")
                ->setParameter("idOrganizer", $idOrganizer);
        }
        /* Si l'id de l'user est donné, on sélectionne que les NightOut auxquelles il participe */
        if(!is_null($idParticipant)){
            dump("Repos add option idParticipant");
            $qb->andWhere("p.id = :idParticipant")
                ->setParameter("idParticipant", $idParticipant);
        }
        /* Dans ce cas on sélectionne toutes les NightOus auxquelles l'user ne participe pas */
        if(!is_null($idNotParticipant)){
            dump("Repos add option idNotParticipant");
            $qb->andWhere("p.id != :idNotParticipant")
                ->setParameter("idNotParticipant", $idNotParticipant );
        }
        $result = $qb->getQuery();
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
