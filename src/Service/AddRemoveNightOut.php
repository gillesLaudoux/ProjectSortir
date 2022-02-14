<?php

namespace App\Service;

use App\Repository\NightOutRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AddRemoveNightOut
{

    public function __construct(NightOutRepository $nightOutRepository, UserRepository $userRepository,
                                EntityManagerInterface $em, Security $security){

        $this->nightOutRepository = $nightOutRepository;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->security = $security;
    }

    public function exec ($idNightOut) : void
    {

        $idUser = $this->security->getUser()->getId(); // récupération de l'id de l'utilisateur connecté
        $nightOut = $this->nightOutRepository->find($idNightOut); // on va cherche en db la NightOut à laquelle on veut
        // ajouter un user

        $user = $this->userRepository->find($idUser); // on va chercher l'user en db

        $userNightsOut = $user->getNightsOut(); // on récupère la liste des NightOut auxquelles participe l'user

        if($userNightsOut->contains($nightOut)) { /* in_array retourne true si l'objet est dans l'array */

            $user->removeNightOut($nightOut);

        } elseif (new \DateTime() < $nightOut->getDueDateInscription() &&
            $nightOut->getParticipants()->count() < $nightOut->getNbInscriptionMax())
            /* vérifie que la date de fin d'inscription n'est pas atteinte ainsi que le nobre max
            de participants "cloture inscription" */
        {
            /* On ajoute la sortie au tableau de l'user, on inscrit l'user à la sortie */
            $user->addNightOut($nightOut);
        }

        $this->em->persist($user);

        $this->em->flush();

    }

}