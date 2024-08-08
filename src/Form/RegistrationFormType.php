<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
// use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email.',
                    ]),
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas une adresse valide.',
                    ]),
                ],
                'label' => 'Email',
            ])


            // SSI ON UTILISE UNE CASE À COCHER POUR ACCEPTER DES CONDITIONS
            // ->add('agreeTerms', CheckboxType::class, [
            // 'mapped' => false,
            // 'constraints' => [
            // new IsTrue([
            // 'message' => 'You should agree to our terms.',
            // ]),
            // ],
            // ])

            // VIEUX: AVANT D'UTILISER UN 2E CHAMP DE CONFIRM POUR LE MDP
            // ->add('plainPassword', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez entrer un mot de passe',
            //         ]),
            //         new Length([
            //             'min' => 8,
            //             'minMessage' => 'Le mot de passe doit compter au minimum {{ limit }} caractères',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //         new \Symfony\Component\Validator\Constraints\Regex([
            //             'pattern' => '/[&)=(?]+/',
            //             'message' => 'Le mot de passe doit contenir au moins l\'un de ces caractères: & ) = ( ?'
            //         ]),
            //         new \Symfony\Component\Validator\Constraints\Regex([
            //             'pattern' => '/[a-z]+/',
            //             'message' => 'Le mot de passe doit contenir au moins une minuscule'
            //         ]),
            //         new \Symfony\Component\Validator\Constraints\Regex([
            //             'pattern' => '/[A-Z]+/',
            //             'message' => 'Le mot de passe doit contenir au moins une majuscule'
            //         ]),
            //         new \Symfony\Component\Validator\Constraints\Regex([
            //             'pattern' => '/[0-9]+/',
            //             'message' => 'Le mot de passe doit contenir au moins un chiffre'
            //         ]),

            //     ],
            // ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrez un mot de passe svp',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Le mot de passe doit compter au moins {{ limit }} caracters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new \Symfony\Component\Validator\Constraints\Regex([
                            'pattern' => '/[&)=(?]+/',
                            'message' => 'Le mot de passe doit contenir au moins l\'un de ces caractères: & ) = ( ?\')'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Regex([
                            'pattern' => '/[a-z]+/',
                            'message' => 'Le mot de passe doit contenir au moins une minuscule'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Regex([
                            'pattern' => '/[A-Z]+/',
                            'message' => 'Le mot de passe doit contenir au moins une majuscule'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Regex([
                            'pattern' => '/[0-9]+/',
                            'message' => 'Le mot de passe doit contenir au moins un chiffre'
                        ]),
                    ],
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe',
                ],
                'invalid_message' => 'Les deux champs ne sont pas identiques.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
