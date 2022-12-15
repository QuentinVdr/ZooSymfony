<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Enclos;
use phpDocumentor\Reflection\Types\InterfaceString;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
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
            ->add('image')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    "Non défini" => "Non défini",
                    "Male" => "Male",
                    "Femelle" => "Femelle",
                ],
            ])
            ->add('sterile')
            ->add('quarantaine')
            ->add('enclos', EntityType::class, [
                'class'=>Enclos::class,
                'choice_label'=>"nom",
                'multiple'=>false,
                'expanded'=>false
            ])
            ->add("ok", SubmitType::class, array(
                "label" => "Ajouter",
                "attr" => array(
                    'class' => 'btn btn-primary',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
