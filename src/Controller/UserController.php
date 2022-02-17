<?php
namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
#[Route("/", name:"")]
class UserController extends AbstractController
{
    #[Route("/login", name:"app_login")]
    public function login(
        AuthenticationUtils $authenticationUtils,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    #[Route("/logout", name:"app_logout")]
    public function logout(): Response
    {
        return $this->redirectToRoute('index');
    }
    /** Gestion du profil */
    #[IsGranted("ROLE_USER")]
    #[Route('/user/{username}', name: 'modify')]
    public function modifierProfil(
        EntityManagerInterface $entityManager,
        Request $request,
                               $username
    ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour !');
//            return $this->render('user/detail.html.twig', ['form' =>$form->createView()]);
            return $this->redirectToRoute('detail',
                ['id'=>$user->getId()]);
        }
        return $this->renderForm('modifyProfile/user.html.twig',
            compact('form')
        );
        //TODO faire message erreur si pas marcher
    }
    /** Affichage d'un profil */
    #[IsGranted("ROLE_USER")]
    #[Route('/user/detail/{id}', name:'detail') ]
    public function detail  (
        UserRepository $userRepository,
                       $id
    ):Response
    {
        $user = $userRepository->find($id);
        dump($user);
        return $this->render('user/detail.html.twig',
            compact("user")
        );
    }
    #[Route('/loginorregister', name: 'index')]
    public function mainpage(
    ): Response
    {
        return $this->render('user/mainpage.html.twig');
    }
}