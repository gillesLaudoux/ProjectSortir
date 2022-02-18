<?php
namespace App\Controller;
use App\Repository\CampusRepository;
use App\Repository\NightOutRepository;
use App\Repository\UserRepository;
use App\Service\AddRemoveNightOut;
use App\Service\majUser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class MainController extends AbstractController
{
    #[IsGranted("ROLE_USER")]
    /** Page principale avec ajout d'un user à une NightOut possible */
        /* Il est nécessaire d'ajouter requirements: ['idNightOut' => '\d+'] pour indiquer que idNightOut est un digit
           Sinon, toute route comme /register déclenche notre index, et crée une erreur plantant l'app */
    #[Route('/{idNightOut}/{theme}', name: 'home', requirements: ['idNightOut' => '\d+'])]
    public function index(NightOutRepository $nightOutRepository, CampusRepository $campusRepository, UserRepository $userRepository,EntityManagerInterface $entityManager,
                          AddRemoveNightOut $addRmv, Request $request, majUser $maj, $idNightOut=0, $theme=-1): Response
    {
        $campusList = $campusRepository->findAll();
        $maj->exec();
dump( $this->getUser());
        if ($idNightOut != 0) {
            $addRmv->exec($idNightOut);
            $nightOutList = $nightOutRepository->selectAll();
        } else {

            if ($theme !== -1) {

                $user = $this->getUser();
                if ($theme == 1 || $theme == null){
                    $user->setTheme('1');
                    $entityManager->persist($user);
                    $entityManager->flush();
                }

                if($theme == 2){
                    dump('please');
                    $user->setTheme('2');
                    $entityManager->persist($user);
                    $entityManager->flush();
                }

            }


            /** On ne chercher pas à faire une nouvelle insertion d'une NightOut, ici on s'en sert pour récupérer des
             * champs, dans le but de filtrer ce qu'on affiche
             */
            $idOrganizer = $request->query->get('is_organizer');
            $idParticipant = $request->query->get('is_participant');
            $idNotParticipant = $request->query->get('not_participant');
            $campus = $request->query->get("filter_night_out_campus");
            $nightOutName = $request->query->get("filter_night_out_name");
            $startDate = $request->query->get("filter_night_out_startTime");
            $endDate = $request->query->get("filter_night_out_endTime");
            if (is_null($campus) && is_null($idOrganizer) && is_null($idParticipant) && is_null($idNotParticipant) &&
                is_null($nightOutName)) {
                $nightOutList = $nightOutRepository->selectAll();
            } else {
                //TODO supprimer les dump à la prod
                // On fait un like sur le nom, en ajoutant les % on fait un un LIKE %name%
                // Cela permet aussi de trouver tout les NightOut ayant certains caractères
                $nightOutName = "%" . $nightOutName . "%";
                /**permet de faire une recherche par mots clés*/
                $nightOutList = $nightOutRepository->selectFilter($campus, $nightOutName, $startDate, $endDate,
                    $idOrganizer, $idParticipant, $idNotParticipant);
            }
        }
//        $formParticiper = $this->createForm(ParticiperNightOutType::class);
//        $formParticiper->handleRequest($request);
        // Requête permettant de sélectionner tous les articles (avec des inner joins) si le formulaire de filtre
        // n'est pas utilisé
        return $this->render('main/index.html.twig',
            compact("nightOutList", "campusList"));
    }
}