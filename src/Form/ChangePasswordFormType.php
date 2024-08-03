<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrez un mot de passe svp',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new \Symfony\Component\Validator\Constraints\Regex([
                            'pattern' => '/[&)=(?]+/',
                            'message' => 'Doit contenir au moins l\'un de ces caractères: & ) = ( ?\')'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Regex([
                            'pattern' => '/[a-z]+/',
                            'message' => 'Doit contenir au moins une minuscule'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Regex([
                            'pattern' => '/[A-Z]+/',
                            'message' => 'Doit contenir au moins une majuscule'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Regex([
                            'pattern' => '/[0-9]+/',
                            'message' => 'Doit contenir au moins un chiffre'
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
