<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Form\AvatarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/avatar', name: 'avatar')]
class AvatarController extends AbstractController
{
    #[Route('/register', name: '_register')]
    public function avatarRegister(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager
    ): Response
    {
        $avatar = new Avatar();
        $avatarForm = $this->createForm(AvatarType::class, $avatar);
        $avatarForm->handleRequest($request);

        if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
            /** @var UploadedFile $avatarFile */
            $avatarFile = $avatarForm->get('Avatar')->getData();
            dump('lol');
            // this condition is needed because the 'avatar' field is not required
            // so the file must be processed only when a file is uploaded
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();
                dump('2');
                // Move the file to the directory where brochures are stored
                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // TODO handle exception if something happens during file upload
                }

                // updates the 'avatarFilename' property to store the file name
                // instead of its contents
                $avatar->setAvatarFileName($newFilename);
                dump('3');
            }
            // ... persist the $product variable or any other work
            $entityManager->persist($avatar);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien ajouté votre photo !');

            return $this->render('avatar/register.html.twig', ['avatarForm' => $avatarForm
                ->createView()]);
        }

        return $this->renderForm('avatar/register.html.twig',
             compact('avatarForm')
        );

    }

    #[Route('/update', name: '_update')]
    public function avatarUpdate(): Response
    {
        return $this->render('avatar/update.html.twig', [
            'controller_name' => 'AvatarController',
        ]);
    }
}
