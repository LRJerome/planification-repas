<?php

namespace App\Form;

use App\Entity\IngredientQuantite;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class IngredientQuantiteType extends AbstractType
{
    private $ingredientRepository;

    public function __construct(IngredientRepository $ingredientRepository)
    {
        $this->ingredientRepository = $ingredientRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'nom',
                'query_builder' => function (IngredientRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nom', 'ASC');
                },
                'choice_attr' => function(Ingredient $ingredient) {
                    return [
                        'data-unite' => $ingredient->getUnite(),
                        'data-nom' => $ingredient->getNom(),
                        'data-id' => $ingredient->getId()
                    ];
                },
                'attr' => [
                    'class' => 'ingredient-select'
                ],
                'placeholder' => 'Choisir un ingrédient',
                'required' => true,
            ])
            ->add('quantite', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'step' => 'any',
                    'class' => 'form-control'
                ],
                'required' => true,
            ])
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        // Si nous avons un ingrédient sélectionné
        if ($form->getData() instanceof IngredientQuantite && $form->getData()->getIngredient()) {
            $ingredient = $form->getData()->getIngredient();
            if (isset($view->children['ingredient'])) {
                $view->children['ingredient']->vars['attr']['data-selected-unite'] = $ingredient->getUnite();
                $view->children['ingredient']->vars['attr']['data-selected-id'] = $ingredient->getId();
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IngredientQuantite::class,
        ]);
    }
}