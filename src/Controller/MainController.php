<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Repository\NightOutRepository;
use App\Repository\UserRepository;
use App\Service\AddRemoveNightOut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /** Page principale avec ajout d'un user à une NightOut possible */
    /* Il est nécessaire d'ajouter requirements: ['idNightOut' => '\d+'] pour indiquer que idNightOut est un digit
       Sinon, toute route comme /register déclenche notre index, et crée une erreur plantant l'app */
    #[Route('/{idNightOut}', name: '_index', requirements: ['idNightOut' => '\d+'])]
    public function index(NightOutRepository $nightOutRepository, CampusRepository $campusRepository,
                          AddRemoveNightOut $addRmv, $idNightOut=0): Response
    {
        $nightOutList = $nightOutRepository->selectAll();
        $campusList = $campusRepository->findAll();

        if($idNightOut!==0){
            $addRmv->exec($idNightOut);
        }

        return $this->render('main/index.html.twig',
            compact('nightOutList', 'campusList')
        );
    }
}
