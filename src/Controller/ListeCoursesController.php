<?php
// src/Controller/ListeCoursesController.php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Entity\Ingredient;
use App\Form\ListeCoursesType;
use App\Form\DateSelectionType;
use App\Repository\PlanningRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\NouvelIngredientType;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListeCoursesController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/liste/courses', name: 'liste_courses_index')]
    public function index(Request $request, SessionInterface $session, PlanningRepository $planningRepository, IngredientRepository $ingredientRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $listeCourses = $session->get('liste_courses_' . $user->getId(), []);
        
        $form = $this->createForm(DateSelectionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $dateDebut = $data['dateDebut'];
            $dateFin = $data['dateFin'];

            $planning = $planningRepository->findByDateRangeAndUser($dateDebut, $dateFin, $user);
            $listeCourses = $this->calculerListeCourses($planning, $ingredientRepository);
            
            // Trier la liste par ordre alphabétique des noms d'ingrédients
            ksort($listeCourses);

            $session->set('liste_courses_' . $user->getId(), $listeCourses);
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
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $dateDebut = new \DateTime();
        $dateFin = (clone $dateDebut)->modify('+7 days'); // +7 pour avoir 8 jours au total (aujourd'hui inclus)
        
        $planning = $planningRepository->findByDateRangeAndUser($dateDebut, $dateFin, $user);
        $listeCourses = $this->calculerListeCourses($planning, $ingredientRepository);
        
        // Trier la liste par ordre alphabétique des noms d'ingrédients
        ksort($listeCourses);

        $session->set('liste_courses_' . $user->getId(), $listeCourses);

        $this->addFlash('success', 'La liste de courses a été générée avec succès pour les 8 prochains jours.');
        return $this->redirectToRoute('liste_courses_index');
    }

    #[Route('/liste-courses/modifier', name: 'liste_courses_modifier', methods: ['GET', 'POST'])]
    public function modifierListeCourses(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $listeCourses = $session->get('liste_courses_' . $user->getId(), []);

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

        $nouvelIngredient = new Ingredient();
        $nouvelIngredientForm = $this->createForm(NouvelIngredientType::class, $nouvelIngredient);

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
            // Trier la liste par ordre alphabétique des noms d'ingrédients
            ksort($newListeCourses);

            $session->set('liste_courses_' . $user->getId(), $newListeCourses);

            $this->addFlash('success', 'La liste de courses a été mise à jour.');
            return $this->redirectToRoute('liste_courses_index');
        }

        return $this->render('liste_courses/modifier.html.twig', [
            'form' => $form->createView(),
            'nouvelIngredientForm' => $nouvelIngredientForm->createView(),
            'debug_liste_courses' => $listeCourses,
        ]);
    }

    #[Route('/liste-courses/ajouter-ingredient', name: 'liste_courses_ajouter_ingredient', methods: ['POST'])]
    public function ajouterIngredient(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $nouvelIngredient = new Ingredient();
        $form = $this->createForm(NouvelIngredientType::class, $nouvelIngredient);
        
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isValid()) {
            $entityManager->persist($nouvelIngredient);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'ingredient' => [
                    'id' => $nouvelIngredient->getId(),
                    'nom' => $nouvelIngredient->getNom(),
                    'unite' => $nouvelIngredient->getUnite()
                ]
            ]);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->getFormErrors($form)
        ], 400);
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

                $nombrePersonnes = $this->getNombrePersonnesParType($jour, $typeRepas);
                
                // Log des informations du repas
                $this->logger->debug('Traitement repas', [
                    'date' => $jour->getDate()->format('Y-m-d'),
                    'type_repas' => $typeRepas,
                    'repas' => $repas->getNom(),
                    'nombre_personnes' => $nombrePersonnes
                ]);
                
                // Si le nombre de personnes est 0 ou null, on saute ce repas
                if ($nombrePersonnes === 0 || $nombrePersonnes === null) {
                    $this->logger->debug('Repas ignoré car nombre de personnes = 0');
                    continue;
                }

                foreach ($repas->getIngredientQuantites() as $ingredientQuantite) {
                    $ingredient = $ingredientQuantite->getIngredient();
                    $quantiteBase = $ingredientQuantite->getQuantite();
                    
                    // Log des calculs
                    $this->logger->debug('Calcul ingrédient', [
                        'ingredient' => $ingredient->getNom(),
                        'quantite_base' => $quantiteBase,
                        'nombre_personnes' => $nombrePersonnes,
                        'calcul' => $quantiteBase * $nombrePersonnes
                    ]);

                    if ($quantiteBase === null) {
                        continue;
                    }

                    $quantiteNecessaire = floatval($quantiteBase) * $nombrePersonnes;
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

        // Log du résultat final
        $this->logger->debug('Liste courses finale', $listeCourses);

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
        // Log pour vérifier la valeur retournée
        $nombre = match ($typeRepas) {
            'petitDejeuner' => $jour->getNombrePersonnesPetitDejeuner(),
            'encasMatin' => $jour->getNombrePersonnesEncasMatin(),
            'dejeuner' => $jour->getNombrePersonnesDejeuner(),
            'encasApresMidi' => $jour->getNombrePersonnesEncasApresMidi(),
            'diner' => $jour->getNombrePersonnesDiner(),
            default => 0
        };
        
        $this->logger->debug("Nombre de personnes pour {$typeRepas}", ['nombre' => $nombre]);
        return $nombre;
    }

    private function getFormErrors($form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
}
