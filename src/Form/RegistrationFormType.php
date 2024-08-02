<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            // ->add('agreeTerms', CheckboxType::class, [
            // 'mapped' => false,
            // 'constraints' => [
            // new IsTrue([
            // 'message' => 'You should agree to our terms.',
            // ]),
            // ],
            // ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mdp doit avoir au minimum {{ limit }} caractères',
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
