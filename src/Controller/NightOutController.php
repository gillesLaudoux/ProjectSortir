<?php

namespace App\Controller;

use App\Form\FilterNightOutType;
use App\Entity\NightOut;
use App\Form\NightOutType;
use App\Repository\StateRepository;
use App\Repository\CampusRepository;
use App\Repository\NightOutRepository;
use App\Repository\UserRepository;
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

    /** Fonction qui permet d'afficher une sortie selon différents filtres */
    #[Route('/nightoutfilter', name: '_filter')]
    public function nightOutFilterCampus(
        NightOutRepository $nightOutRepository,
        Request            $request
    ): Response
    {
        $newNightOut = new NightOut();

        $filterForm = $this->createForm(FilterNightOutType::class, $newNightOut);
        $filterForm->handleRequest($request);
        /** On ne chercher pas à faire une nouvelle insertion d'une NightOut, ici on s'en sert pour récupérer des
         * champs, dans le but de filtrer ce qu'on affiche
         */
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            // On récupére ici les champs servant au filtre
            $campus = $newNightOut->getCampus()->getName();
            $nightOutName = $newNightOut->getName();
            $startDate = $newNightOut->getStartingTime();
            $endDate = $newNightOut->getDueDateInscription();
//            $isOrganizer = $request->query->get('is_organizer');

            dump("On rentre dans le if");

            dump($campus);
            dump($nightOutName);
            dump($startDate);
            dump($endDate);
//TODO supprimer les dump à la prod

            // Si les champs de Date ne sont pas remplies, on les initialise à des dates qui ne perturbent pas notre
            // filtre
            if (empty($startDate)) {
                $startDate = new \DateTime();
                dump("On initialise startdate");
            }
            if (empty($endDate)) {
                $endDate = new \DateTime("2023-01-01");
                dump("On initialise endate");
            }

            // On fait un like sur le nom, en ajoutant les % on fait un un LIKE %name%
            // Cela permet aussi de trouver tout les NightOut ayant certains caractères
            $nightOutName = "%" . $nightOutName . "%"; /**permet de faire une recherche par mots clés*/
            $nightOutList = $nightOutRepository->selectFilter($campus, $nightOutName, $startDate, $endDate);

        } else {
            // Requête permettant de sélectionner tous les articles (avec des inner joins) si le formulaire de filtre
            // n'est pas utilisé
            $nightOutList = $nightOutRepository->selectAll();
        }

        dump($nightOutList);

        return $this->renderForm('main/index.html.twig',
            compact("nightOutList", "filterForm")
        );

    }
    #[Route('/create', name: '_create')]
    public function CreateNightOut(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        StateRepository $stateRepository,
        Request $request
    ):Response{

        $nightOut = new NightOut();
        $formNight = $this->createForm(NightOutType::class, $nightOut);
        $formNight->handleRequest($request);
        if($formNight->isSubmitted() && $formNight->isValid()){
            //Vérification des dates pour l'inscription
            //$isDateValide =  $verifdate->DateDiff($nightOut->$this->getDueDateInscription(),
            // $nightOut->getStartingTime());
            //$isDateValide2 = $verifdate->DateDiff(,$nightOut->getDueDateInscription())
            //   //TODO en cours : verif dates

            /** Recupération de l'ID de la personne co en passant par le Repository afin d'assigner l'objet Organisateur
             * portant cet ID en tant oragnisateur de la soirée
             */
            $nightOut->setOrganizer($userRepository->find($this->getUser()->getId()));

            /**  Linkage des bouttons pour le submit des etats selon les idées */
            if ($formNight->get('enregistrer')->isClicked()){
                $nightOut->SetState($stateRepository->find(1));
            }
            else if ($formNight->get('publier')->isClicked()){
                $nightOut->SetState($stateRepository->find(2));

            }

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
