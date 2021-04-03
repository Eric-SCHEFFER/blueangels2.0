<?php

namespace App\Form;

use App\Entity\InfosEtAdresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfosEtAdressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_entreprise')
            ->add('adresse')
            ->add('code_postal')
            ->add('ville')
            ->add('telephone')
            ->add('email_contact')
            ->add('email_envoi_formulaire')
            ->add('facebook')
            ->add('youtube')
            ->add('telephone_2')
            ->add('complement_adresse')
            ->add('autre')
            ->add('lieu_cours')
            ->add('adresse_cours')
            ->add('code_postal_cours')
            ->add('ville_cours')
            ->add('google_maps_cours')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InfosEtAdresses::class,
        ]);
    }
}
