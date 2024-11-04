<?php

namespace App\Form;

use App\Entity\Planning;
use App\Entity\Repas;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('petitDejeuner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un repas',
                'choice_attr' => function(Repas $repas) {
                    return [
                        'data-category' => $repas->getCategorie()
                    ];
                },
            ])
            ->add('nombrePersonnesPetitDejeuner', IntegerType::class, [
                'required' => false,
                'attr' => ['min' => 1]
            ])
            ->add('encasMatin', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un repas',
                'choice_attr' => function(Repas $repas) {
                    return [
                        'data-category' => $repas->getCategorie()
                    ];
                },
            ])
            ->add('nombrePersonnesEncasMatin', IntegerType::class, [
                'required' => false,
                'attr' => ['min' => 1]
            ])
            ->add('dejeuner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un repas',
                'choice_attr' => function(Repas $repas) {
                    return [
                        'data-category' => $repas->getCategorie()
                    ];
                },
            ])
            ->add('nombrePersonnesDejeuner', IntegerType::class, [
                'required' => false,
                'attr' => ['min' => 1]
            ])
            ->add('encasApresMidi', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un repas',
                'choice_attr' => function(Repas $repas) {
                    return [
                        'data-category' => $repas->getCategorie()
                    ];
                },
            ])
            ->add('nombrePersonnesEncasApresMidi', IntegerType::class, [
                'required' => false,
                'attr' => ['min' => 1]
            ])
            ->add('diner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un repas',
                'choice_attr' => function(Repas $repas) {
                    return [
                        'data-category' => $repas->getCategorie()
                    ];
                },
            ])
            ->add('nombrePersonnesDiner', IntegerType::class, [
                'required' => false,
                'attr' => ['min' => 1]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}