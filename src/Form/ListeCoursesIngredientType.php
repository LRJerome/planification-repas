<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\IngredientRepository;

class ListeCoursesIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'nom',
                'choice_attr' => function(Ingredient $ingredient) {
                    return [
                        'data-unite' => $ingredient->getUnite(),
                        'data-quantite-defaut' => $ingredient->getQuantiteDefaut()
                    ];
                },
                'query_builder' => function (IngredientRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('LOWER(i.nom)', 'ASC');
                },
                'required' => true,
            ])
            ->add('quantite', NumberType::class, [
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'step' => 'any',
                    'class' => 'form-control quantity-input'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
} 