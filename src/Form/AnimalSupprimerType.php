<?php

namespace App\Form;

use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalSupprimerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identification')
            ->add('nom')
            ->add('dateNaissance')
            ->add('dateArrivee')
            ->add('dateDepart')
            ->add('zooProprietaire')
            ->add('genre')
            ->add('espece')
            ->add('sexe')
            ->add('sterile')
            ->add('quarantaine')
            ->add('enclos')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
