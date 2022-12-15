<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Enclos;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\PseudoTypes\NumericString;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identification', NumberType::class, array(
                'label' => "Identification (14 chiffres)",
                'attr' => array(
                    'maxlength' => 14,
                    'minlength' => 14,
                ))
            )
            ->add('nom')
            ->add('dateNaissance')
            ->add('dateArrivee')
            ->add('dateDepart')
            ->add('zooProprietaire')
            ->add('genre')
            ->add('espece')
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
