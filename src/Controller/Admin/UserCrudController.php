<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
//     Pour shunter les contraintes d'intégrité
//    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
//    {
//        if(!$entityInstance instanceof User) return;
//        //$entityInstance->setCampus(new );
//
//        parent::persistEntity($em, $entityInstance);
//    }

}
