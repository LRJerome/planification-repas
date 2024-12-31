<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Form\RepasType;
use App\Entity\Ingredient;
use App\Entity\IngredientQuantite;
use App\Form\IngredientType;
use App\Repository\RepasRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Définition du préfixe de route pour toutes les méthodes de ce contrôleur
#[Route('/repas', name: '')]
class RepasController extends AbstractController
{
    // Route pour afficher la liste des repas
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, RepasRepository $repasRepository, IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager): Response
    {
        $ingredientId = $request->query->get('ingredient');
        
        // Récupération des repas filtrés par ingrédient ou de tous les repas
        if ($ingredientId) {
            $repas = $repasRepository->findByIngredient($ingredientId);
        } else {
            $repas = $repasRepository->findAllOrderedByName();
        }
        
        // Nettoyage des ingrédients invalides
        foreach ($repas as $recipe) {
            foreach ($recipe->getIngredientQuantites() as $iq) {
                if ($iq->getIngredient() === null) {
                    $entityManager->remove($iq);
                }
            }
        }
        $entityManager->flush();
        
        return $this->render('repas/index.html.twig', [
            'repas' => $repas,
            'ingredients' => $ingredientRepository->findAll(),
            'selectedIngredient' => $ingredientId
        ]);
    }

    // Route pour afficher le sommaire des repas par catégorie
    #[Route('/sommaire', name: 'repas_sommaire', methods: ['GET'])]
    public function sommaire(EntityManagerInterface $entityManager): Response
    {
        $repasRepository = $entityManager->getRepository(Repas::class);
        $tousLesRepas = $repasRepository->findAll();
        
        // Initialisation des catégories de repas
        $repasParCategorie = [
            'low-carb' => [],
            'post-training' => [],
            'en-cas' => [],
            'petit-dejeuner' => []
        ];

        // Compteur pour chaque catégorie
        $compteurParCategorie = [
            'low-carb' => 0,
            'post-training' => 0,
            'en-cas' => 0,
            'petit-dejeuner' => 0
        ];

        // Classification des repas par catégorie
        foreach ($tousLesRepas as $repas) {
            $categorie = strtolower($repas->getCategorie());
            
            if (!isset($repasParCategorie[$categorie])) {
                $categorie = 'petit-dejeuner';
            }
            
            $repasParCategorie[$categorie][] = $repas;
            $compteurParCategorie[$categorie]++; // Incrémente le compteur
        }

        // Trier les repas dans chaque catégorie par nom
        foreach ($repasParCategorie as $categorie => &$repas) {
            usort($repas, function($a, $b) {
                return strcmp($a->getNom(), $b->getNom());
            });
        }

        return $this->render('repas/sommaire.html.twig', [
            'repas_par_categorie' => $repasParCategorie,
            'compteur_par_categorie' => $compteurParCategorie // Ajout du compteur
        ]);
    }

    // Route pour créer un nouveau repas
    #[Route('/new', name: 'app_repas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, IngredientRepository $ingredientRepository): Response
    {
        $repas = new Repas();
        $ingredientQuantite = new IngredientQuantite();
        $ingredientQuantite->setRepas($repas);
        $repas->addIngredientQuantite($ingredientQuantite);
        
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Vérification des champs obligatoires
                if (empty($repas->getRecette())) {
                    $this->addFlash('error', 'Les instructions sont obligatoires');
                    return $this->render('repas/new.html.twig', [
                        'form' => $form->createView(),
                        'repas' => $repas
                    ]);
                }

                $repas->setDate(new \DateTime());
                
                // Persistance du repas et des IngredientQuantite associés
                $entityManager->persist($repas);
                
                foreach ($repas->getIngredientQuantites() as $ingredientQuantite) {
                    $ingredientQuantite->setRepas($repas);
                    $entityManager->persist($ingredientQuantite);
                }
                
                $entityManager->flush();
                
                $this->addFlash('success', 'La recette a été créée avec succès !');
                return $this->redirectToRoute('index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
            }
        }

        return $this->render('repas/new.html.twig', [
            'form' => $form->createView(),
            'repas' => $repas
        ]);
    }

    // Route pour modifier un repas existant
    #[Route('/{id}/edit', name: 'repas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repas $repas, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Persistance des IngredientQuantite modifiés
                foreach ($repas->getIngredientQuantites() as $ingredientQuantite) {
                    $ingredientQuantite->setRepas($repas);
                    $entityManager->persist($ingredientQuantite);
                }
                
                $entityManager->flush();
                
                $this->addFlash('success', 'La recette a été modifiée avec succès !');
                return $this->redirectToRoute('index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la modification : ' . $e->getMessage());
            }
        }

        return $this->render('repas/edit.html.twig', [
            'repas' => $repas,
            'form' => $form->createView(),
        ]);
    }

    // Route pour supprimer un repas
    #[Route('/{id}', name: 'repas_delete', methods: ['POST'])]
    public function delete(Request $request, Repas $repas, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repas->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repas);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index');
    }

    // Route pour afficher les détails d'un repas
    #[Route('/{id}', name: 'repas_show', methods: ['GET'])]
    public function show(Request $request, Repas $repas): Response
    {
        // Récupération du nombre de personnes depuis l'URL
        $nombrePersonnes = $request->query->get('nombrePersonnes');

        if (!empty($nombrePersonnes)) {
            $nombrePersonnes = (int) $nombrePersonnes;
        } else {
            $nombrePersonnes = null;
        }

        return $this->render('repas/show.html.twig', [
            'repas' => $repas,
            'nombrePersonnes' => $nombrePersonnes,
        ]);
    }
}