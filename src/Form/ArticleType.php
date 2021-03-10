<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use App\Entity\CategoriesArticle;



class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [])
            ->add('hook')

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

            ->add('created_at')
            ->add('contenu')

            ->add('epingle', CheckboxType::class, [
                'required' => false,
                'label' => 'Épingler'
            ])

            ->add('CategoriesArticle', EntityType::class, [
                'class' => CategoriesArticle::class,
                'label' => 'Catégorie',
                'required' => false,
                'placeholder' => 'choisir une option',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
            
        ]);
    }
}
