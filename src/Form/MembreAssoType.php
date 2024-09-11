<?php

namespace App\Form;

use App\Entity\MembresAsso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MembreAssoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('fonction')
            ->add('description')

            ->add('photo', FileType::class, [
                'required' => false,
                'label' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        // maxSize: Par fichier
                        'maxSize' => '8M',
                        'maxSizeMessage' => 'Trop gros: 8M maxi par fichier',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Fichier non-valide: Uniquement jpeg et png',
                        'allowLandscape' => false,
                        'allowLandscapeMessage' => 'Image carrée uniquement (actuelle fait {{ width }} x {{ height }} px)',
                        'allowPortrait' => false,
                        'allowPortraitMessage' => 'Image carrée uniquement (actuelle fait {{ width }} x {{ height }} px)',
                        'allowSquare' => true,
                        'allowSquareMessage' => 'Message allowSquare',
                    ])
                ]
            ])


            ->add('email')
            ->add('facebook')
            ->add('telephone')

            ->add('actif', CheckboxType::class, [
                'required' => false,
                'label' => 'Actif',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MembresAsso::class,
        ]);
    }
}
