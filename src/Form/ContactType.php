<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public const NOM_CHAMP_POT_DE_MIEL = 'email';
    public const NOM_CHAMP_EMAIL = 'adresse_electronique';


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Minimum 3 caractères',
                        'max' => 255,
                        'maxMessage' => 'Maximum 255 caractères'
                    ])
                ]
            ])

            ->add('nom', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Minimum 3 caractères',
                        'max' => 255,
                        'maxMessage' => 'Maximum 255 caractères'
                    ])
                ]
            ])

            // Faux champ email caché (anti-spam pot de miel)
            ->add(self::NOM_CHAMP_POT_DE_MIEL, EmailType::class, [
                'required' => false,
                'attr' => []
            ])

            ->add('tel', textType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Minimum 3 caractères',
                        'max' => 255,
                        'maxMessage' => 'Maximum 255 caractères'
                    ])
                ]
            ])

            ->add(self::NOM_CHAMP_EMAIL, EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Email([
                        'message' => 'l\'adresse email n\'est pas valide'
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Maximum 255 caractères'
                    ])

                ]
            ])

            ->add('objet', TextType::class, [
                'attr' => [],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Minimum 3 caractères',
                        'max' => 255,
                        'maxMessage' => 'Maximum 255 caractères'
                    ])
                ]
            ])

            ->add('message', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Minimum 3 caractères',
                        'max' => 4096,
                        'maxMessage' => 'Maximum 4096 caractères'
                    ])
                ]
            ])



            ->add('envoyer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
