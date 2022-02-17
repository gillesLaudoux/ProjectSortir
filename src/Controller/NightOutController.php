<?php

namespace App\Controller;


use App\Entity\NightOut;
use App\Form\NightOutType;
use App\Form\NightOutUpdateType;
use App\Form\ParticiperNightOutType;
use App\Repository\StateRepository;
use App\Repository\CampusRepository;
use App\Repository\NightOutRepository;
use App\Repository\UserRepository;
use App\Service\AddRemoveNightOut;
use App\Service\verifdate;
use PhpParser\Node\Expr\New_;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nightout', name: 'night_out')]
class NightOutController extends AbstractController
{

    #[IsGranted("ROLE_USER")]
    #[Route('/create', name: '_create')]
    public function CreateNightOut(
        EntityManagerInterface $entityManager,
        UserRepository         $userRepository,
        StateRepository        $stateRepository,
        Request                $request,
        verifdate              $verifdate
    ): Response
    {

        $nightOut = new NightOut();
        $formNight = $this->createForm(NightOutType::class, $nightOut);
        $formNight->handleRequest($request);

        $isDateValide = $verifdate->DateDiff($nightOut->getDueDateInscription(), $nightOut->getStartingTime());


            if ($formNight->isSubmitted() && $formNight->isValid()) {
               // dd('abc');
                if($isDateValide) {
                //Vérification des dates pour l'inscription
                /** Si $isDateValide === true -> date 1 inférieure à date deux -> dateValide   */


                //   //TODO en cours : verif dates

                /** Recupération de l'ID de la personne co en passant par le Repository afin d'assigner l'objet Organisateur
                 * portant cet ID en tant oragnisateur de la soirée
                 */
                $user = $userRepository->find($this->getUser()->getId());
                $nightOut->setOrganizer($user);
                $nightOut->addParticipant($user);
                //$nightOut->setOrganizer($this->getUser());

                /**  Linkage des bouttons pour le submit des etats selon les idées */
                if ($formNight->get('enregistrer')->isClicked()) {
                    $nightOut->SetState($stateRepository->find(1));
                    dump('Enregistrer');
                } else if ($formNight->get('publier')->isClicked()) {
                    $nightOut->SetState($stateRepository->find(2));

                }

                $entityManager->persist($nightOut);
//                    unset($nightOut);
//                    unset($formNight);
//
//                    $nightOut = new NightOut();
//                    $formNight = $this->createForm(NightOutType::class, $nightOut);

                $entityManager->flush();

//                    $this->addFlash('fail', "Ta sortie a bien été mise en ligne!! :)");

                return $this->render('night_out/index.html.twig');

            }
                else {
                    $this->addFlash('fail', "Ta sortie n'a pas été prise en compte, tu as dû faire une erreur de saisie quelque part!! :(");
                }
        }


        return $this->renderForm('night_out/create.html.twig',
            compact('formNight')
        );


    }

    /**  Update de l'événement */
    #[IsGranted("ROLE_USER")]
    #[Route('/update/{id}', name: '_update')]
    public function updateNightOut(
        EntityManagerInterface $entityManager,
        Request                $request,
        NightOutRepository     $nightOutRepository,
        StateRepository        $stateRepository,
                               $id
    ): Response
    {
        dump($id);
        $nightOut = $nightOutRepository->find($id);
        $formUpdateNightOut = $this->createForm(NightOutUpdateType::class, $nightOut);
        $formUpdateNightOut->handleRequest($request);
        if ($formUpdateNightOut->isSubmitted() && $formUpdateNightOut->isValid()) {
            dump($nightOut->GetState()->getId());

            /** ↓  Si le bouton supprimer est cliqué ET que la soirée est publiée  OU   que le bouton supprimer n'a pas été cliqué  ->  Quand on veut modifier la donnée et pas la supprimer en gros  ↓ */
            if (($formUpdateNightOut->get('supprimer')->isClicked() && ($nightOut->getState() === $stateRepository->find(2))) || !($formUpdateNightOut->get('supprimer')->isClicked())) {
                /**Evenement en etat Créé*/
                if ($formUpdateNightOut->get('enregistrer')->isClicked() && $nightOut->GetState()->getId() == 1) {
                    $nightOut->SetState($stateRepository->find(1));
                } /**  Evenvement en état Ouvert*/
                else if ($formUpdateNightOut->get('publier')->isClicked()) {
                    $nightOut->SetState($stateRepository->find(2));
                }
                if ($formUpdateNightOut->get('supprimer')->isClicked() && $nightOut->getState() === $stateRepository->find(2)) {
                    $nightOut->SetState($stateRepository->find(6));
                }

                $entityManager->persist($nightOut);
                $entityManager->flush();

            }
            /**Suppression de l'évènement si l'état est en Créé et annulation de l'évènement si l'état est en Ouvert */
            if ($formUpdateNightOut->get('supprimer')->isClicked() && $nightOut->getState() === $stateRepository->find(1)) {
                $entityManager->remove((object)$nightOut);
                $entityManager->flush();
            }

            $this->addFlash('success', 'Vous avez bien modifié votre événement ! Bien ouej');
            return $this->render('night_out/update.html.twig', ['formUpdateNightOut' => $formUpdateNightOut
                ->createView()]);
        }
        if ($formUpdateNightOut->isSubmitted() && !$formUpdateNightOut->isValid()) {
            $this->addFlash('fail', "Votre modification n'a pas été prise en compte.");
        }
        return $this->renderForm('night_out/update.html.twig',
            compact('formUpdateNightOut')
        );

    }

    /**  Affichage de l'événement */
    #[IsGranted("ROLE_USER")]
    #[Route('/detail/{id}', name: '_detail')]
    public function detail(
        NightOutRepository $nightOutRepository,
        $id
    ):Response
    {
        $nightOut = $nightOutRepository->find($id);
        $participants = $nightOut->getParticipants();
        return $this->render('night_out/detail.html.twig', compact('nightOut', 'participants'));


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
