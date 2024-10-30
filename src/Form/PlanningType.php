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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('petitDejeuner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesPetitDejeuner', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ])
            ->add('encasMatin', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesEncasMatin', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ])
            ->add('dejeuner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesDejeuner', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ])
            ->add('encasApresMidi', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesEncasApresMidi', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ])
            ->add('diner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesDiner', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}