<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',null, ["label" => "Pseudo : "])
            //->add('roles')
            //->add('password')
            ->add('email', null, ["label" => "Email : "])
            ->add('firstName', null, ["label" => "Prénom : "])
            ->add('lastName',null, ["label" => "Nom de famille : "])
            ->add('phoneNumber',null, ["label" => "Numéro de téléphone : "])
            //->add('administrator')
            //->add('isActivated')
            //->add('nightsOut')
            //->add('campus',null, ["label" => "Campus ENI : "])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
