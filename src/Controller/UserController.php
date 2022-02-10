<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


#[Route("/", name:"")]
class UserController extends AbstractController
{

     #[Route("/login", name:"app_login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
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

    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    //Gestion du profil
    #[Route('/user/{username}', name: 'modify')]
    public function modifierProfil(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien modifié votre profil ! Bien ouej');

            return $this->render('modifyProfile/user.html.twig', ['form' =>$form->createView()]);
        }
        return $this->renderForm('modifyProfile/user.html.twig',
            compact('form')
        );
        //TODO faire message erreur si pas marcher

    }

}
