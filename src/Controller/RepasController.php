<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Form\RepasType;
use App\Repository\RepasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RepasController extends AbstractController
{
    #[Route("/repas", name:"repas_index", methods:['GET'])]
    public function index(Request $request, RepasRepository $repasRepository): Response
    {
        $categories = ['low_carb', 'post_training', 'en_cas', 'autre'];
        
        // Récupérer les catégories sélectionnées depuis la requête
        $selectedCategories = $request->query->all('categories', []);
        
        // Si aucune catégorie n'est sélectionnée, utiliser toutes les catégories
        if (empty($selectedCategories)) {
            $selectedCategories = $categories;
        }

        // Assurez-vous que $selectedCategories est un tableau
        $selectedCategories = (array) $selectedCategories;

        $repas = $repasRepository->findByCategories($selectedCategories);

        return $this->render('repas/index.html.twig', [
            'repas' => $repas,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
        ]);
    }

    #[Route('/repas/new', name: 'repas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repas = new Repas();
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($repas->getIngredientQuantites() as $ingredientQuantite) {
                $repas->addIngredient($ingredientQuantite['ingredient']);
                // Ici, vous pouvez gérer la quantité si nécessaire
            }
            $entityManager->persist($repas);
            $entityManager->flush();

            return $this->redirectToRoute('repas_index');
        }

        return $this->render('repas/new.html.twig', [
            'repas' => $repas,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/repas/{id}/edit', name: 'repas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repas $repas, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('repas_index');
        }

        return $this->render('repas/edit.html.twig', [
            'repas' => $repas,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/repas/{id}', name: 'repas_delete', methods: ['POST'])]
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