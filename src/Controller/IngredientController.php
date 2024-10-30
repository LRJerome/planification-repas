<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

    #[Route('/ingredient')]
    class IngredientController extends AbstractController
    {
        private $logger;

        public function __construct(LoggerInterface $logger)
        {
            $this->logger = $logger;
        }

        #[Route('/', name: 'app_ingredient_index', methods: ['GET'])]
        public function index(IngredientRepository $ingredientRepository): Response
        {
            return $this->render('ingredient/index.html.twig', [
                'ingredients' => $ingredientRepository->findAll(),
            ]);
        }

        #[Route('/new', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager): Response
            {
                $ingredient = new Ingredient();
                $form = $this->createForm(IngredientType::class, $ingredient);
                $form->handleRequest($request);
            
                if ($request->isXmlHttpRequest()) {
                    if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager->persist($ingredient);
                        $entityManager->flush();
            
                        return $this->json([
                            'success' => true,
                            'ingredient' => [
                                'id' => $ingredient->getId(),
                                'nom' => $ingredient->getNom(),
                            ],
                        ]);
                    }
            
                    // Si le formulaire n'est pas valide, renvoyer les erreurs
                    $errors = [];
                    foreach ($form->getErrors(true) as $error) {
                        $errors[] = $error->getMessage();
                    }
            
                    return $this->json([
                        'success' => false,
                        'error' => implode(', ', $errors),
                    ], 400);
                }
            
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($ingredient);
                    $entityManager->flush();
            
                    return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
                }
            
                return $this->render('ingredient/new.html.twig', [
                    'ingredient' => $ingredient,
                    'form' => $form,
                ]);
            }
        
        

    #[Route('/{id}', name: 'app_ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            try {
                // Supprimer d'abord les relations dans repas_ingredient
                $repas = $ingredient->getRepas();
                foreach ($repas as $repa) {
                    $repa->removeIngredient($ingredient);
                }

                // Supprimer les ListeCourses associées si nécessaire
                foreach ($ingredient->getListeCourses() as $listeCourse) {
                    $entityManager->remove($listeCourse);
                }

                $entityManager->remove($ingredient);
                $entityManager->flush();

                $this->logger->info('Ingrédient supprimé avec succès', ['id' => $ingredient->getId()]);
                $this->addFlash('success', 'L\'ingrédient a été supprimé avec succès.');
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

        return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}

