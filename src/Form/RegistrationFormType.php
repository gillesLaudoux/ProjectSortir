<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Sodium\add;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('username', null, [
                'attr' => ["class" => "app_register form text username",
                ],
                "required" => true,
                "label"=> false
            ])

            ->add('email', EmailType::class,
            ["attr"=>["class"=>"app_register form email"],
                "label"=> false
            ])

            ->add('firstName', null, [
                'attr'=>["class"=>"app_register form text firstName"],
                "label"=> false,
                "required"=>true])

            ->add('lastName', null, [
                "attr"=>["class"=>"app_register form text lastName"],
                "required"=>true,
                "label"=> false
            ])

            ->add('phoneNumber', null, [
                "attr"=>["class"=>"app_register form text phoneNumber"],
                "label"=> false
            ])

            ->add('campus', EntityType::class,
                    ["class"=>Campus::class,
                        "choice_label"=>"name",
                        "label"=> false,
                        "attr"=>["class"=>"app_register form entityCampus"]])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                "label" => "Veuillez accepter nos conditions :",
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                "label"=> false,
                'attr' => ['autocomplete' => 'new-password',
                    "class"=>"app_register form password"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
