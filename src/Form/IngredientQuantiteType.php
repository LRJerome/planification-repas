<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientQuantite;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotNull;

class IngredientQuantiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'nom',
                'query_builder' => function (IngredientRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('LOWER(i.nom)', 'ASC');
                },
                'choice_attr' => function(Ingredient $ingredient) {
                    return [
                        'data-unite' => $ingredient->getUnite(),
                        'data-quantite-defaut' => $ingredient->getQuantiteDefaut()
                    ];
                },
                'placeholder' => 'Choisir un ingrédient',
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Veuillez sélectionner un ingrédient'
                    ])
                ]
            ])
            ->add('quantite', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Quantité',
                    'step' => 'any',
                    'min' => 0
                ],
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'La quantité est requise'
                    ]),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'La quantité doit être supérieure à 0'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IngredientQuantite::class,
        ]);
    }
}