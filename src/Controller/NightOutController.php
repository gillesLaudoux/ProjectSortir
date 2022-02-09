<?php

namespace App\Controller;

use App\Form\FilterNightOutType;
use App\Entity\NightOut;
use App\Form\NightOutType;
use App\Repository\NightOutRepository;
use mysql_xdevapi\Result;
use PhpParser\Node\Expr\New_;
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
        $nightOutList = $nightOutRepository->selectAll();

        return $this->render('main/index.html.twig',
            compact('nightOutList')
        );


    }

   #[Route('/nightoutfilter', name: 'night_out_filter_campus')]
    public function nightOutFilterCampus(
        NightOutRepository $nightOutRepository,
        Request $request
/*        $campus,
        $nightOutName = "",
        $startDate  = "",
        $endDate = ""*/
    ): Response
    {
        $newNightOut = new NightOut();

        $filterForm = $this->createForm(FilterNightOutType::class, $newNightOut);
        $filterForm->handleRequest($request);
        if ($filterForm ->isSubmitted() && $filterForm->isValid()){
            $campus = $newNightOut->getCampus()->getName();
            $nightOutName = $newNightOut->getName();
            $startDate = $newNightOut->getStartingTime();
            $endDate = $newNightOut->getDueDateInscription();
            dump("On rentre dans le if");

            dump($campus);
            dump($nightOutName);
            dump($startDate);
            dump($endDate);

            if (empty($startDate))
            {
                $startDate= new \DateTime();
                dump("On initialise startdate");
            }
            if (empty($endDate))
            {
                $endDate= new \DateTime("2023-01-01");
                dump("On initialise endate");
            }

            $nightOutName = "%".$nightOutName."%";
            $nightOutList = $nightOutRepository->selectFilter($campus, $nightOutName, $startDate,$endDate);

        } else {

            $nightOutList = $nightOutRepository->selectAll();
        }

        dump($nightOutList);

        return $this->renderForm('main/index.html.twig',
            compact("nightOutList", "filterForm")
        );


    }
//#[Route('/create', name: '_create')]
//    public function CreateNightOut(
//        EntityManagerInterface $entityManager,
//        Request $request
//    ):Response{
//
//        $nightOut = new NightOut();
//        $formNight = $this->createForm(NightOutType::class, $nightOut);
//        $formNight->handleRequest($request);
//        if($formNight->isSubmitted() && $formNight->isValid()){
//            $entityManager->persist($nightOut);
//            $entityManager->flush();
//
//            return $this->renderForm('night_out/create.html.twig',
//            compact("formNight")
//            );
//        }
//        return $this->renderForm('night_out/create.html.twig',
//            compact('formNight')
//        );
//    }
//


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
