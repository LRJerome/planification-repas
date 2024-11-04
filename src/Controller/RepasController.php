<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Form\RepasType;
use App\Entity\Ingredient;
use App\Repository\RepasRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/repas', name: '')]
class RepasController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, RepasRepository $repasRepository, IngredientRepository $ingredientRepository): Response
    {
        $ingredientId = $request->query->get('ingredient');
        
        // Récupérer toutes les recettes ou les recettes filtrées
        if ($ingredientId) {
            $repas = $repasRepository->findByIngredient($ingredientId);
        } else {
            $repas = $repasRepository->findAll();
        }
        
        return $this->render('repas/index.html.twig', [
            'repas' => $repas,
            'ingredients' => $ingredientRepository->findAll(),
            'selectedIngredient' => $ingredientId
        ]);
    }

    #[Route('/sommaire', name: 'repas_sommaire', methods: ['GET'])]
    public function sommaire(EntityManagerInterface $entityManager): Response
    {
        $repasRepository = $entityManager->getRepository(Repas::class);
        $tousLesRepas = $repasRepository->findAll();
        
        $repasParCategorie = [
            'low_carb' => [],
            'post_training' => [],
            'en_cas' => [],
            'autre' => []
        ];

        foreach ($tousLesRepas as $repas) {
            $categorie = str_replace('-', '_', strtolower($repas->getCategorie()));
            
            if (!isset($repasParCategorie[$categorie])) {
                $categorie = 'autre';
            }
            
            $repasParCategorie[$categorie][] = $repas;
        }

        return $this->render('repas/sommaire.html.twig', [
            'repas_par_categorie' => $repasParCategorie
        ]);
    }

    #[Route('/new', name: 'app_repas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repas = new Repas();
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Définir la date
                $repas->setDate(new \DateTime());
                
                // Persister les relations
                foreach ($repas->getIngredientQuantites() as $ingredientQuantite) {
                    $ingredientQuantite->setRepas($repas);
                    $entityManager->persist($ingredientQuantite);
                }
                
                $entityManager->persist($repas);
                $entityManager->flush();
                
                $this->addFlash('success', 'La recette a été créée avec succès !');
                return $this->redirectToRoute('index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
            }
        } elseif ($form->isSubmitted()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('repas/new.html.twig', [
            'form' => $form,
            'repas' => $repas,
        ]);
    }

    #[Route('/{id}/edit', name: 'repas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repas $repas, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('repas/edit.html.twig', [
            'repas' => $repas,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'repas_delete', methods: ['POST'])]
    public function delete(Request $request, Repas $repas, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repas->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repas);
            $entityManager->flush();
        }

        return $this->redirectToRoute('repas_index');
    }

    #[Route('/{id}', name: 'repas_show', methods: ['GET'])]
    public function show(Repas $repas): Response
    {
        return $this->render('repas/show.html.twig', [
            'repas' => $repas,
        ]);
    }
}