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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;
use App\Entity\CategoriesArticle;



class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [])

            ->add('hook', TextType::class, [
                'required' => true,
                'label' => 'Phrase d\'accroche',
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

            ->add('created_at', DateTimeType::class, [
                'label' => 'Date de mise à jour',
                'required' => true
            ])

            ->add('contenu');

        // ->add('CategoriesArticle', EntityType::class, [
        //     'class' => CategoriesArticle::class,
        //     'label' => 'Catégorie',
        //     'required' => false,
        //     'placeholder' => 'Aucune catégorie choisie',
        // ])

        // On utilise à la place de ci-dessus, l'option queryBuilder pour trier les choix par nom
        $builder->add('CategoriesArticle', EntityType::class, [
            'class' => CategoriesArticle::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'placeholder' => 'Aucune catégorie choisie',
            'label' => 'Catégorie',
            'required' => false,
        ])

            ->add('linked_page', CheckboxType::class, [
                'required' => false,
                'label' => 'Lié à une page fixe'
            ])

            ->add('epingle', CheckboxType::class, [
                'required' => false,
                'label' => 'Épingler en page d\'accueil'
            ])

            ->add('actif', CheckboxType::class, [
                'required' => false,
                'label' => 'Actif',
            ])
            ->add('listed', CheckboxType::class, [
                'required' => false,
                'label' => 'Listé dans home et menu articles',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,

        ]);
    }
}
