<?php

namespace App\Repository;

use App\Entity\NightOut;
use App\Entity\User;
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
            ->innerJoin("n.organizer", "org")
            ->innerJoin("pl.city", "ci");

        return $qb->getQuery()->getResult();
    }

    /** INNER JOIN sur les tables necessaire à la filtration de la recherche */
    public function selectFilter($campusName, $nightOutName, $startDate, $endDate, $idOrganizer, $idParticipant,
                                 $idNotParticipant)
    {
        $qb = $this->createQueryBuilder("n");

        $qb->leftJoin("n.state", "s")
            ->leftJoin("n.category", "cat")
            ->leftJoin("n.places", "pl")
            ->leftJoin("n.campus", "cam")
            ->leftJoin("n.participants", "p")
            ->leftJoin("n.organizer", "org")
            ->leftJoin("pl.city", "ci")
            ->where("n.state = 2");// affiche que les night_out ouvertes

        if(!is_null($nightOutName)){
            $qb->andWhere('n.name LIKE :nightOutName')
                ->setParameter("nightOutName", "%".$nightOutName."%");
        }

        dump($startDate);
        dump($endDate);

        if(!empty($startDate)){
            $qb->andWhere('n.startingTime > :startingTime')
                ->setParameter("startingTime", $startDate);
        }

        if(!empty($endDate)){
            $qb->andWhere('n.dueDateInscription < :endTime')
                ->setParameter("endTime", $endDate);
        }

        if(!is_null($campusName)){
            $qb->andWhere('cam.name = :name')
                ->setParameter("name", $campusName);
        }

        /* Si l'id d'un organizer est donné, on sélectionne que les NightOut qu'il organise */
        if(!is_null($idOrganizer)){
            $user = $this->getEntityManager()->getRepository(User::class)->find($idOrganizer);
            $qb->andWhere("org.id = :organizer")
                ->setParameter("organizer", $user);
        }
        /* Si l'id de l'user est donné, on sélectionne que les NightOut auxquelles il participe */
        if(!is_null($idParticipant)){
            $user = $this->getEntityManager()->getRepository(User::class)->find($idParticipant);
            $qb->andWhere(":participant MEMBER OF n.participants")
                ->setParameter("participant", $user);
        }
        /* Dans ce cas on sélectionne toutes les NightOus auxquelles l'user ne participe pas */
        if(!is_null($idNotParticipant)){
            $user = $this->getEntityManager()->getRepository(User::class)->find($idNotParticipant);
            $qb->andWhere(":participant NOT MEMBER OF n.participants")
                ->setParameter("participant", $user );
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
