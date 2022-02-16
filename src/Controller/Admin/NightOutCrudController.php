<?php

namespace App\Controller\Admin;

use App\Entity\NightOut;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NightOutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NightOut::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            DateTimeField::new('startingTime'),
            DateTimeField::new('dueDateInscription'),
            NumberField::new('nbInscriptionMax'),
            TextEditorField::new('description'),
            //AssociationField::new('participants'),
            //AssociationField::new('organizer'),
            //AssociationField::new('campus'),
            //AssociationField::new('places'),
            //AssociationField::new('state'),
            //AssociationField::new('category'),
            DateTimeField::new('endingTime'),
        ];
    }

}
