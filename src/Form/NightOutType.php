<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Category;
use App\Entity\NightOut;
use App\Entity\Place;
use Doctrine\ORM\Query\AST\DeleteStatement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NightOutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label'=>"Nom de l'événement"])
            ->add('startingTime', null, ['label'=>"Date de l'événement",
                'date_widget'=>'single_text', 'time_widget'=>'single_text'])
            ->add('endingTime', null, ['label'=>"Fin de l'évènement : ",
                'date_widget'=>'single_text', 'time_widget'=>'single_text'])
            ->add('dueDateInscription', null, ['label'=>"Date de fin d'inscription : ",
                'date_widget'=>'single_text', 'time_widget'=>'single_text'])
            ->add('nbInscriptionMax', null, ['label'=>"Nombre de places"])
            //->add('participants', null, ['label'=>"participants : "])
            //->add('organizer', null, ['label'=>''])
            //->add('state', null, ['label'=>"Etat de l'évènement"])
            //TODO gérer state
            ->add('campus', EntityType::class,
                ["class"=>Campus::class,
                    "choice_label"=>"name",
                    'label' => "Campus ENI : "])
            ->add('places', EntityType::class,
                ['class'=>Place::class,
                    "choice_label"=>"name",
                    'label' => "Lieu de l'événement : "])
            ->add('category', EntityType::class, [
                "class"=>Category::class,
                "choice_label"=>"libelle",
                'label' => "Type d'événement : "])
            ->add('description', null, [
                'label'=>"Description : "])
            ->add('enregistrer',SubmitType::class, [
                'label' => 'Enregistrer'])
            ->add('publier',SubmitType::class, [
                'label' => 'Publier'])


        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NightOut::class,
        ]);
    }
}
