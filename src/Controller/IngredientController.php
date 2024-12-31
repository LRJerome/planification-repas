<?php

namespace App\Controller;

use App\Entity\IngredientQuantite;
use Psr\Log\LoggerInterface;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

// Définition du préfixe de route pour toutes les méthodes de ce contrôleur
#[Route('/ingredient', name: 'ingredient_')]
class IngredientController extends AbstractController
{
    private $logger;

    // Injection du logger dans le constructeur
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    // Route pour afficher la liste des ingrédients
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAllOrderedByName()
        ]);
    }

    // Route pour créer un nouvel ingrédient
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Vérification des champs requis
                if (empty($ingredient->getNom())) {
                    throw new \Exception('Le nom de l\'ingrédient est obligatoire');
                }
                if (empty($ingredient->getUnite())) {
                    throw new \Exception('L\'unité de mesure est obligatoire');
                }
                if ($ingredient->getQuantiteDefaut() === null) {
                    throw new \Exception('La quantité par défaut est obligatoire');
                }

                $entityManager->persist($ingredient);
                $entityManager->flush();

                // Gestion du cas où la requête provient d'une popup
                if ($request->query->has('popup')) {
                    return $this->render('ingredient/close.html.twig', [
                        'ingredient' => $ingredient,
                        'success' => true
                    ]);
                }

                $this->addFlash('success', 'L\'ingrédient a été créé avec succès !');
                return $this->redirectToRoute('ingredient_index');
            } catch (\Exception $e) {
                // Gestion des erreurs spécifiques
                $message = match (true) {
                    str_contains($e->getMessage(), 'Column \'quantite\' cannot be null') => 'La quantité est obligatoire',
                    str_contains($e->getMessage(), 'Integrity constraint violation') => 'Cet ingrédient existe déjà',
                    default => 'Une erreur est survenue lors de la création de l\'ingrédient'
                };

                if ($request->query->has('popup')) {
                    return $this->render('ingredient/new.html.twig', [
                        'ingredient' => $ingredient,
                        'form' => $form,
                        'isPopup' => true,
                        'error' => $message
                    ]);
                }

                $this->addFlash('error', $message);
            }
        }

        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
            'isPopup' => $request->query->has('popup')
        ]);
    }

    // Route pour afficher les détails d'un ingrédient
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    // Route pour modifier un ingrédient existant
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    // Route pour supprimer un ingrédient
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            try {
                // Récupération et suppression des IngredientQuantite associés
                $ingredientQuantites = $entityManager->getRepository(IngredientQuantite::class)
                    ->findBy(['ingredient' => $ingredient]);
                
                foreach ($ingredientQuantites as $iq) {
                    $entityManager->remove($iq);
                }
                
                // Suppression de l'ingrédient
                $entityManager->remove($ingredient);
                $entityManager->flush();
                
                $this->addFlash('success', 'L\'ingrédient a été supprimé avec succès.');
                
            } catch (\Exception $e) {
                // Journalisation de l'erreur
                $this->logger->error('Erreur lors de la suppression de l\'ingrédient', [
                    'id' => $ingredient->getId(),
                    'error' => $e->getMessage()
                ]);
                $this->addFlash('error', 'Erreur lors de la suppression : ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('ingredient_index', [], Response::HTTP_SEE_OTHER);
    }

    // Route pour créer un nouvel ingrédient via une requête AJAX
    #[Route('/new/ajax', name: 'new_ajax', methods: ['POST'])]
    public function newAjax(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('ingredient_new_ajax', $request->request->get('_token'))) {
            return new JsonResponse(['error' => 'Token CSRF invalide'], 422);
        }

        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        
        $data = $request->request->all();
        $form->submit($data['ingredient'] ?? $data);

        if ($form->isValid()) {
            try {
                $entityManager->persist($ingredient);
                $entityManager->flush();

                // Retour d'une réponse JSON en cas de succès
                return new JsonResponse([
                    'success' => true,
                    'id' => $ingredient->getId(),
                    'nom' => $ingredient->getNom(),
                    'unite' => $ingredient->getUnite()
                ]);
            } catch (\Exception $e) {
                // Retour d'une réponse JSON en cas d'erreur
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Erreur lors de la création : ' . $e->getMessage()
                ], 400);
            }
        }

        // Retour d'une réponse JSON si le formulaire est invalide
        return new JsonResponse([
            'success' => false,
            'error' => 'Formulaire invalide'
        ], 400);
    }
}