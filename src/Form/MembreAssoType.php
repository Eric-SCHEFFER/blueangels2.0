<?php

namespace App\Form;

use App\Entity\MembresAsso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\MembresAssoRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


class MembreAssoType extends AbstractType
{
    private $membresAssoRepository;
    public function __construct(MembresAssoRepository $membresAssoRepository)
    {
        $this->membresAssoRepository = $membresAssoRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => True,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'max' => 255,
                        'maxMessage' => 'Maximum {{ limit }} caractères'
                    ])
                ]
            ])

            ->add('nom', TextType::class, [
                'required' => True,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'max' => 255,
                        'maxMessage' => 'Maximum {{ limit }} caractères'
                    ])
                ]
            ])

            ->add('fonction', TextType::class, [
                'required' => True,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'max' => 255,
                        'maxMessage' => 'Maximum {{ limit }} caractères'
                    ])
                ]
            ])

            ->add('email', EmailType::class, [
                'required' => False,
                'constraints' => [
                    new Email([
                        'message' => 'l\'adresse email n\'est pas valide'
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Maximum {{ limit }} caractères'
                    ])
                ]
            ])

            ->add('facebook', UrlType::class, [
                'required' => False,
                'constraints' => [
                    new Url([
                        'message' => 'Ce n\'est pas une url valide'
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Maximum {{ limit }} caractères'
                    ])
                ]
            ])

            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'required' => False,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'max' => 255,
                        'maxMessage' => 'Maximum {{ limit }} caractères'
                    ])
                ]
            ])

            ->add('actif', CheckboxType::class, [
                'required' => false,
                'label' => 'Actif',
            ])

            ->add('description', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'max' => 4096,
                        'maxMessage' => 'Maximum {{ limit }} caractères'
                    ])
                ]
            ]);

        // On ajoute l'inputFile (photo) uniquement s'il n'y a pas déjà une image en base
        if (empty($this->getPhoto($options))) {
            $builder->add('photo', FileType::class, [
                'required' => false,
                'label' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '8M',
                        'maxSizeMessage' => 'Trop gros: 8M maxi par fichier',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Fichier non-valide: Uniquement jpeg et png',
                        'allowLandscape' => false,
                        'allowLandscapeMessage' => 'Image carrée uniquement (celle-ci fait {{ width }} x {{ height }} px)',
                        'allowPortrait' => false,
                        'allowPortraitMessage' => 'Image carrée uniquement (celle-ci fait {{ width }} x {{ height }} px)',
                        'allowSquare' => true,
                        'allowSquareMessage' => 'Message allowSquare',
                    ])
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MembresAsso::class,
        ]);
    }

    /**
     * On retourne le nom de fichier en bdd de la photo du trombinoscopé
     */
    private function getPhoto($options)
    {
        $id = $options["data"]->getId();
        if (isset($id)) {
            $photo = $this->membresAssoRepository->find($id)->getphoto();
            return $photo;
        }
    }
}
