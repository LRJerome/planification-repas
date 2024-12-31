<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'ingrédient',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Farine, Sucre, Lait...'
                ]
            ])
            ->add('unite', ChoiceType::class, [
                'label' => 'Unité',
                'required' => true,
                'choices' => [
                    'Grammes' => 'g',
                    'Millilitres' => 'ml',
                    'Centilitres' => 'cl',
                    'Pièce' => 'piece',
                    'Cuillère à soupe' => 'cas',
                    'Cuillère à café' => 'cac',
                    'Pincée' => 'pincée'
                    
                ],
                'choice_translation_domain' => false,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->add('quantiteDefaut', NumberType::class, [
                'label' => 'Quantité par défaut',
                'required' => false,
                'data' => 1.0,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => 'any',
                    'placeholder' => 'Ex: 100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}