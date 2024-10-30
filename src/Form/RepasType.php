<?php

namespace App\Form;

use App\Entity\Repas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom du repas'],
                'label' => 'Nom du repas',
            ])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Low-carb' => 'low_carb',
                    'Post-training' => 'post_training',
                    'En-cas' => 'en_cas',
                    'Autre' => 'autre',
                ],
                'label' => 'Type de recettes',
                'required' => true,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('description', null, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Description du repas', 'rows' => 4],
                'label' => 'Description',
            ])
            ->add('recette', null, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Détails de la recette', 'rows' => 6],
                'label' => 'Recette',
            ])
            ->add('ingredientQuantites', CollectionType::class, [
                'entry_type' => IngredientQuantiteType::class, // On utilise ici le formulaire `IngredientQuantiteType`
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'attr' => ['class' => 'ingredient-collection'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repas::class,
        ]);
    }
}
