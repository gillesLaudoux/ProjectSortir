<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NightOutController extends AbstractController
{
    #[Route('/night/out', name: 'night_out')]
    public function index(): Response
    {
        return $this->render('night_out/index.html.twig', [
            'controller_name' => 'NightOutController',
        ]);
    }
}
