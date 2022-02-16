<?php

namespace App\Form;

use App\Entity\Avatar;
use App\Entity\Campus;
use App\Entity\File;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',null, ["label" => false])
            //->add('roles')
            //->add('password')
            ->add('email', null, ["label" => false])
            ->add('firstName', null, ["label" => false])
            ->add('lastName',null, ["label" => false])
            ->add('phoneNumber',null, ["label" => false])
            //->add('administrator')
            //->add('isActivated')
            //->add('nightsOut')
            ->add('campus',EntityType::class,
                ['class'=>Campus::class,
                    "label" => false]);
//            ->add('avatar',
//                EntityType::class,
//                [
//                    'class'=>Avatar::class,
//                    //'image_uri' =>true,
//                ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
