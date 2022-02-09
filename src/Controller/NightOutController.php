<?php

namespace App\Controller;

use App\Entity\NightOut;
use App\Form\NightOutType;
use App\Repository\NightOutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nightout', name: 'night_out')]
class NightOutController extends AbstractController
{
    #[Route('index', name: '_index')]
    public function index(NightOutRepository $nightOutRepository): Response
    {
        $result = $nightOutRepository->selectAll();
        dump($result);
        return $this->render('main/index.html.twig');

    }

    #[Route('/create', name: '_create')]
    public function CreateNightOut(
        EntityManagerInterface $entityManager,
        Request $request
    ):Response{

        $nightOut = new NightOut();
        $formNight = $this->createForm(NightOutType::class, $nightOut);
        $formNight->handleRequest($request);
        if($formNight->isSubmitted() && $formNight->isValid()){
            $entityManager->persist($nightOut);
            $entityManager->flush();

            return $this->renderForm('night_out/create.html.twig',
            compact("formNight")
            );
        }
        return $this->renderForm('night_out/create.html.twig',
            compact('formNight')
        );
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
