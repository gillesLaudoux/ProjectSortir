<?php

namespace App\Controller;

use App\Form\FilterNightOutType;
use App\Entity\NightOut;
use App\Form\NightOutType;
use App\Form\NightOutUpdateType;
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
        NightOutRepository $nightOutRepository, CampusRepository $campusRepository,
        Request            $request
    ): Response
    {
        $campusList = $campusRepository->findAll();
        /** On ne chercher pas à faire une nouvelle insertion d'une NightOut, ici on s'en sert pour récupérer des
         * champs, dans le but de filtrer ce qu'on affiche
         */
        $isOrganizer = $request->query->get('is_organizer');
        $isParticipant = $request->query->get('is_participant');
        $notParticipant = $request->query->get('not_participant');
        dump("Nos variables :");
        $campus = $request->query->get("filter_night_out_campus");
        $nightOutName = $request->query->get("filter_night_out_name");
        $startDate = $request->query->get("filter_night_out_startTime");
        $endDate = $request->query->get("filter_night_out_endTime");
        if (is_null($campus)&&is_null($isOrganizer)&&is_null($isParticipant)&&is_null($notParticipant)&&
            is_null($nightOutName)){
            $nightOutList = $nightOutRepository->selectAll();
        } else {
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
            if(!is_null($isOrganizer)){ // si on veut les sorties dont l'user est l'organisateur, on va chercher son id
                // pour l'utiliser avec le Repo
                $idOrganizer = $this->getUser()->getId();
            } else {$idOrganizer=null;}
            if(!is_null($isParticipant)){
                $idParticipant = $this->getUser()->getId();
            } else {$idParticipant=null;}
            if(!is_null($notParticipant)){
                $idNotParticipant = $this->getUser()->getId();
            }else {$idNotParticipant=null;}
            dump($campus);
            dump($nightOutName);
            dump($isOrganizer);
            dump($isParticipant);
            dump($notParticipant);
            dump($startDate);
            dump($endDate);
            dump($idOrganizer);
            dump($idParticipant);
            dump($idNotParticipant);
//TODO supprimer les dump à la prod
            // On fait un like sur le nom, en ajoutant les % on fait un un LIKE %name%
            // Cela permet aussi de trouver tout les NightOut ayant certains caractères
            $nightOutName = "%" . $nightOutName . "%"; /**permet de faire une recherche par mots clés*/
            $nightOutList = $nightOutRepository->selectFilter($campus, $nightOutName, $startDate, $endDate,
                $idOrganizer, $idParticipant, $idNotParticipant);
        }
        dump($endDate);
        dump($startDate);
        // Requête permettant de sélectionner tous les articles (avec des inner joins) si le formulaire de filtre
        // n'est pas utilisé
        dump($nightOutList);
        return $this->render('main/index.html.twig',
            compact("nightOutList", "campusList")
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
                dump('Enregistrer');
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

    /**  Update de l'événement */
    #[Route('/update/{id}', name: '_update')]
    public function updateNightOut(
        EntityManagerInterface $entityManager,
        Request $request,
        NightOutRepository $nightOutRepository,
        StateRepository $stateRepository,
        $id
    ): Response{

    dump($id);

        $nightOut = $nightOutRepository->find($id);
        $formUpdateNightOut = $this->createForm(NightOutUpdateType::class, $nightOut );
        $formUpdateNightOut->handleRequest($request);

        if ($formUpdateNightOut->isSubmitted() && $formUpdateNightOut->isValid()) {

            dump($nightOut->GetState()->getId());

            /**Evenement en etat Créé*/
            if ($formUpdateNightOut->get('enregistrer')->isClicked() && $nightOut->GetState()->getId() == 1){
                $nightOut->SetState($stateRepository->find(1));
            }

            /**  Evenvement en état Ouvert*/
            else if ($formUpdateNightOut->get('publier')->isClicked()){
                $nightOut->SetState($stateRepository->find(2));
            }

                /**Suppression de l'évènement si l'état est en Créé et annulation de l'évènement si l'état est en Ouvert */
            if ($formUpdateNightOut->get('supprimer')->isClicked() && $nightOut->getState() === $stateRepository->find(1)) {

                $entityManager->remove((object)$nightOut);
                $entityManager->flush();
            }
            if ($formUpdateNightOut->get('supprimer')->isClicked() && $nightOut->getState() === $stateRepository->find(2)){
                $nightOut->SetState($stateRepository->find(6));
            }

            $entityManager->persist($nightOut);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien modifié votre événement ! Bien ouej');

            return $this->render('night_out/update.html.twig', ['formUpdateNightOut' =>$formUpdateNightOut
                ->createView()]);
        }

        if ($formUpdateNightOut->isSubmitted() && !$formUpdateNightOut->isValid()) {
            $this->addFlash('fail', "Votre modification n'a pas été prise en compte.");
        }

        return $this->renderForm('night_out/update.html.twig',
            compact('formUpdateNightOut')
        );


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
}
