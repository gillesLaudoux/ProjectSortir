<?php

namespace App\Service;

use App\Repository\NightOutRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class majUser
{
    public function __construct(UserRepository $userRepository,
                                EntityManagerInterface $em, Security $security){

        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->security = $security;

    }

    public function exec () {

        $user = $this->security->getUser();

        if(!is_null($user)){

            $idUser = $user->getId();

            $user = $this->userRepository->find($idUser);

            dump($user->getAdministrator());
            dump($user->getIsActivated());

            if ($user->getAdministrator()===true){
                $user->setRoles(["ROLE_ADMIN"]);
            }

            if($user->getIsActivated()===false){
                $user->setRoles(["ROLE_NULL"]);
            } else {
                $user->setRoles(["ROLE_USER"]);
            }

            $this->em->persist($user);
            $this->em->flush();

        }

    }

}