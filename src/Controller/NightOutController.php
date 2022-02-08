<?php

namespace App\Controller;

use App\Repository\NightOutRepository;
use mysql_xdevapi\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NightOutController extends AbstractController
{
    #[Route('/nightout', name: 'night_out')]
    public function index(NightOutRepository $nightOutRepository): Response
    {
        $result = $nightOutRepository->selectAll();
        dump($result);
        return $this->render('main/index.html.twig');

    }

//    #[Route('/nightout', name: 'night_out')]
//    public function selectAll(
//
//
//    ): Response
//    {
//        return $this->render('night_out/index.html.twig', [
//            'controller_name' => 'NightOutController',
//        ]);
//    }

}
