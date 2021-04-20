<?php

namespace App\Form;

use App\Entity\Events;
use PhpParser\Node\Expr\Cast\Bool_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [])

            ->add('hook', TextType::class, [
                'required' => true,
                'label' => 'Phrase d\'accroche'
            ])

            // On ajoute le champ image dans le formulaire
            // Il n'est pas lié à la base de données (mapped à false)
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'constraints' => [
                    new All([
                        new Image([
                            // maxSize: Par fichier
                            'maxSize' => '8M',
                            'maxSizeMessage' => 'Trop gros: 8M maxi par fichier',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png'
                            ],
                            'mimeTypesMessage' => 'Fichier non-valide: Uniquement jpeg et png'
                        ])
                    ])
                ]
            ])

            ->add('date_event', DateTimeType::class, [
                'required' => true,
                'label' => 'Date Évènement',
            ])

            ->add('description', TextareaType::class, [])

            ->add('annule', CheckboxType::class, [
                'required' => false,
                'label' => 'Annulé',
            ])

            ->add('actif', CheckboxType::class, [
                'required' => false,
                'label' => 'Actif',
            ])

            ->add('event_blue_angels', CheckboxType::class, [
                'required' => false,
                'label' => 'Organisé par les Blue Angels',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
