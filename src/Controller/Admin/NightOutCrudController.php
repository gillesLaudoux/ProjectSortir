<?php

namespace App\Controller\Admin;

use App\Entity\NightOut;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NightOutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NightOut::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
