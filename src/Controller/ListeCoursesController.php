<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Entity\Ingredient;
use App\Form\ListeCoursesType;
use App\Form\DateSelectionType;
use App\Form\IngredientQuantiteType;
use App\Repository\PlanningRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListeCoursesController extends AbstractController
{
    #[Route('/liste/courses', name: 'liste_courses_index')]
    public function index(Request $request, SessionInterface $session, PlanningRepository $planningRepository, IngredientRepository $ingredientRepository): Response
    {
        $listeCourses = $session->get('liste_courses', []);
        
        $form = $this->createForm(DateSelectionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $dateDebut = $data['dateDebut'];
            $dateFin = $data['dateFin'];

            $planning = $planningRepository->findByDateRange($dateDebut, $dateFin);
            $listeCourses = $this->calculerListeCourses($planning, $ingredientRepository);
            
            $session->set('liste_courses', $listeCourses);
            $this->addFlash('success', 'La liste de courses a été générée avec succès.');
        }

        return $this->render('liste_courses/index.html.twig', [
            'listeCourses' => $listeCourses,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/liste-courses/generer', name: 'liste_courses_generer', methods: ['GET'])]
    public function genererListeCourses(
        PlanningRepository $planningRepository, 
        IngredientRepository $ingredientRepository,
        SessionInterface $session
    ): Response {
        $planning = $planningRepository->findByWeek(new \DateTime());
        $listeCourses = $this->calculerListeCourses($planning, $ingredientRepository);
        
        $session->set('liste_courses', $listeCourses);

        $this->addFlash('success', 'La liste de courses a été générée avec succès.');
        return $this->redirectToRoute('liste_courses_index');
    }

    #[Route('/liste-courses/modifier', name: 'liste_courses_modifier', methods: ['GET', 'POST'])]
    public function modifierListeCourses(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $listeCourses = $session->get('liste_courses', []);

        $ingredients = [];
        foreach ($listeCourses as $nomIngredient => $item) {
            $ingredient = $entityManager->getRepository(Ingredient::class)->findOneBy(['nom' => $nomIngredient]);
            if ($ingredient) {
                $ingredients[] = [
                    'ingredient' => $ingredient,
                    'quantite' => $item['quantite']
                ];
            }
        }

        $form = $this->createForm(ListeCoursesType::class, ['ingredients' => $ingredients]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $newListeCourses = [];
            foreach ($data['ingredients'] as $item) {
                if ($item['ingredient'] && $item['quantite'] > 0) {
                    $newListeCourses[$item['ingredient']->getNom()] = [
                        'quantite' => $item['quantite'],
                        'unite' => $item['ingredient']->getUnite()
                    ];
                }
            }
            $session->set('liste_courses', $newListeCourses);

            $this->addFlash('success', 'La liste de courses a été mise à jour.');
            return $this->redirectToRoute('liste_courses_index');
        }

        return $this->render('liste_courses/modifier.html.twig', [
            'form' => $form->createView(),
            'debug_liste_courses' => $listeCourses,
        ]);
    }

    private function calculerListeCourses($planning, $ingredientRepository)
    {
        $listeCourses = [];
        $typesRepas = ['petitDejeuner', 'encasMatin', 'dejeuner', 'encasApresMidi', 'diner'];

        foreach ($planning as $jour) {
            foreach ($typesRepas as $typeRepas) {
                $repas = $this->getRepasParType($jour, $typeRepas);
                if (!$repas) {
                    continue;
                }

                $nombrePersonnes = $this->getNombrePersonnesParType($jour, $typeRepas) ?: 1;

                foreach ($repas->getIngredients() as $ingredient) {
                    $quantiteDefaut = $ingredient->getQuantiteDefaut();
                    if ($quantiteDefaut === null) {
                        continue;
                    }

                    $quantiteNecessaire = floatval($quantiteDefaut) * $nombrePersonnes;
                    $nomIngredient = $ingredient->getNom();

                    if (!isset($listeCourses[$nomIngredient])) {
                        $listeCourses[$nomIngredient] = [
                            'quantite' => 0,
                            'unite' => $ingredient->getUnite() ?: ''
                        ];
                    }
                    $listeCourses[$nomIngredient]['quantite'] += $quantiteNecessaire;
                }
            }
        }

        // Arrondir les quantités à l'entier supérieur
        foreach ($listeCourses as &$item) {
            $item['quantite'] = ceil($item['quantite']);
        }

        return $listeCourses;
    }

    private function getRepasParType($jour, $typeRepas)
    {
        switch ($typeRepas) {
            case 'petitDejeuner':
                return $jour->getPetitDejeuner();
            case 'encasMatin':
                return $jour->getEncasMatin();
            case 'dejeuner':
                return $jour->getDejeuner();
            case 'encasApresMidi':
                return $jour->getEncasApresMidi();
            case 'diner':
                return $jour->getDiner();
            default:
                return null;
        }
    }

    private function getNombrePersonnesParType($jour, $typeRepas)
    {
        switch ($typeRepas) {
            case 'petitDejeuner':
                return $jour->getNombrePersonnesPetitDejeuner() ?: 1;
            case 'encasMatin':
                return $jour->getNombrePersonnesEncasMatin() ?: 1;
            case 'dejeuner':
                return $jour->getNombrePersonnesDejeuner() ?: 1;
            case 'encasApresMidi':
                return $jour->getNombrePersonnesEncasApresMidi() ?: 1;
            case 'diner':
                return $jour->getNombrePersonnesDiner() ?: 1;
            default:
                return 1;
        }
    }
}
