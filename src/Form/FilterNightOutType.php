<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\NightOut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use function Sodium\add;

class FilterNightOutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                "label"=>"Le nom de la sortie contient :",
                "required"=>false
            ])
            ->add('startingTime', null, [
                "label"=>"Entre le "
            ])
            ->add('dueDateInscription', null, [
                "label"=>"et le "
            ])
           ->add('campus', EntityType::class, ["class"=> Campus::class, "choice_label"=>"name"]);
//            ->add('category');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NightOut::class,
        ]);
    }
}
