<?php

namespace App\Form;

use App\Entity\Repas;
use App\Entity\IngredientQuantite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Count;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RepasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire'
                    ])
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'required' => true,
                'choices' => Repas::CATEGORIES,
                'constraints' => [
                    new NotBlank([
                        'message' => 'La catégorie est obligatoire'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('recette', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Les instructions sont obligatoires'
                    ])
                ]
            ])
            ->add('ingredientQuantites', CollectionType::class, [
                'entry_type' => IngredientQuantiteType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true,
                'label' => false,
                'entry_options' => [
                    'label' => false
                ],
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Vous devez ajouter au moins un ingrédient'
                    ])
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image',
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
                'label' => 'Image de la recette',
                'by_reference' => false
            ])
        ;

        // Ajouter un écouteur pour s'assurer que les relations sont correctement définies
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $repas = $event->getData();
            
            // S'assurer que chaque IngredientQuantite est lié au Repas
            foreach ($repas->getIngredientQuantites() as $ingredientQuantite) {
                if (!$ingredientQuantite->getRepas()) {
                    $ingredientQuantite->setRepas($repas);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Repas::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'repas_new',
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
