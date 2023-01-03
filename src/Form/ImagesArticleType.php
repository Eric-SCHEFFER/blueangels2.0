<?php

namespace App\Form;

use App\Entity\ImagesArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;




class ImagesArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'caption',
                TextType::class,
                [
                    'label' => 'Légende',
                    'constraints' => [
                        new Length([
                            'max' => 60,
                            'maxMessage' => 'Maximum 60 caractères'
                        ])
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImagesArticle::class,

        ]);
    }
}
