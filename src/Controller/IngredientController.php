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

#[Route('/ingredient', name: 'ingredient_')]
class IngredientController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAllOrderedByName()
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($ingredient);
                $entityManager->flush();

                // Passer l'objet ingredient directement
                return $this->render('ingredient/close_and_refresh.html.twig', [
                    'ingredient' => $ingredient
                ]);

            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création de l\'ingrédient : ' . $e->getMessage());
            }
        }
    
        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

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

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            try {
                // Vérification si l'ingrédient est utilisé dans des recettes
                $recettesCount = $entityManager->getRepository(IngredientQuantite::class)
                    ->count(['ingredient' => $ingredient]);
                
                if ($recettesCount > 0) {
                    throw new \Exception('Cet ingrédient est utilisé dans une ou plusieurs recettes.');
                }
                
                $entityManager->remove($ingredient);
                $entityManager->flush();
                
                $this->addFlash('success', 'L\'ingrédient a été supprimé avec succès.');
            } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
                $this->logger->error('Erreur lors de la suppression de l\'ingrédient - Contrainte de clé étrangère', [
                    'id' => $ingredient->getId(),
                    'error' => $e->getMessage()
                ]);
                $this->addFlash('error', 'Impossible de supprimer cet ingrédient car il est utilisé dans une ou plusieurs recettes.');
            } catch (\Exception $e) {
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

    #[Route('/new/ajax', name: 'new_ajax', methods: ['POST'])]
    public function newAjax(
        Request $request, 
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        try {
            // Log les données reçues
            $this->logger->debug('Contenu de la requête:', [
                'content' => $request->getContent(),
                'headers' => $request->headers->all()
            ]);

            // Décoder les données JSON
            $data = json_decode($request->getContent(), true);
            
            // Vérifier si le décodage JSON a réussi
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erreur de décodage JSON: ' . json_last_error_msg());
            }

            // Vérifier les données requises
            if (empty($data['nom'])) {
                throw new \Exception('Le nom est requis');
            }

            // Créer et persister l'ingrédient
            $ingredient = new Ingredient();
            $ingredient->setNom($data['nom']);
            if (isset($data['unite'])) {
                $ingredient->setUnite($data['unite']);
            }

            $entityManager->persist($ingredient);
            $entityManager->flush();

            // Retourner la réponse de succès
            return new JsonResponse([
                'success' => true,
                'ingredient' => [
                    'id' => $ingredient->getId(),
                    'nom' => $ingredient->getNom(),
                    'unite' => $ingredient->getUnite()
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            // Log l'erreur complète
            $this->logger->error('Erreur lors de la création de l\'ingrédient', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->getContent()
            ]);

            // Retourner une réponse d'erreur détaillée
            return new JsonResponse([
                'success' => false,
                'error' => 'Erreur lors de la création de l\'ingrédient',
                'details' => $e->getMessage(),
                'debug' => [
                    'exception_type' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
