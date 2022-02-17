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

            if ($user->getAdministrator()===1){
                $user->setRoles(["ROLE_ADMIN"]);
            }

            if($user->getIsActivated()===0){
                $user->setRoles([""]);
            } else {
                $user->setRoles(["ROLE_USER"]);
            }

            $this->em->persist($user);
            $this->em->flush();

        }

    }

}