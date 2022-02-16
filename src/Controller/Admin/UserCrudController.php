<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use http\Client\Request;
use http\Env\Response;

class UserCrudController extends AbstractCrudController
{
    public const AVATAR_BASE_PATH = 'uploads/avatar';
    public const AVATAR_UPLOAD_DIR ='public/uploads/avatar';
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username'),
            TextField::new('password'),
            EmailField::new ('email'),
            TextField::new('firstname'),
            TextField::new('lastname'),
            TelephoneField::new('phoneNumber'),
            BooleanField::New('administrator'),
            BooleanField::New('isActivated'),
            AssociationField::new ('nightsOut')->hideOnForm(),
//            AssociationField::new('nightOutOrganizer') ,
            AssociationField::new ('campus'),

            AssociationField::new ('avatar')->hideOnForm()
                  //->setBasePath(self::AVATAR_BASE_PATH)
                  //->setUploadDir(self::AVATAR_UPLOAD_DIR)
                  ->setSortable(false),

//           TextEditorField::new('description'),
        ];
    }

//    public function GrantAdministrator(
//        EntityManagerInterface $em,
//        UserRepository$userRepository,
//        Request $request
//    ): void
//    {
//
//        //Pour activier les role selon les valeurs en BDD depuis le dashboard admin
//        $user = $userRepository->findOneBy(['username'=>$request->request->get('username', '')]);
//
//        if($user->getAdministrator()===1){
//            $user->setRoles(["ROLE_ADMIN"]);
//        } elseif ($user->getIsActivated()===1){
//            $user->setRoles(["ROLE_PARTICIPANT"]);
//        } else {
//            $user->setRoles(["ROLE_USER"]);
//        }
//
//        $em->persist($user);
//        $em->flush();
//
//    }

}
