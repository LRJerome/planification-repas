<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use Psr\Log\LoggerInterface;
use App\Repository\RepasRepository;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/planning', name: 'planning_')]
class PlanningController extends AbstractController
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private PlanningRepository $planningRepository;

    public function __construct(
        LoggerInterface $logger, 
        EntityManagerInterface $entityManager,
        PlanningRepository $planningRepository
    ) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->planningRepository = $planningRepository;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository): Response
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $endDate = (clone $today)->modify('+7 days')->setTime(23, 59, 59);
        
        $plannings = [];
        $currentDate = clone $today;
        
        while ($currentDate <= $endDate) {
            $planning = $planningRepository->findOneByDateWithMeals($currentDate);
            
            $dateInfo = [
                'date' => clone $currentDate,
                'planning' => $planning
            ];
            
            $plannings[] = $dateInfo;
            $currentDate->modify('+1 day');
        }

        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
        ]);
    }


    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($planning);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le planning a été créé avec succès.');
            return $this->redirectToRoute('planning_index');
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Le planning a été mis à jour avec succès.');
            return $this->redirectToRoute('planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($planning);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le planning a été supprimé avec succès.');
        }

        return $this->redirectToRoute('planning_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add-repas/{id}', name: 'add_repas', methods: ['POST'])]
    public function addRepasToPlanning(
        int $id, 
        Request $request, 
        RepasRepository $repasRepository
    ): Response {
        $date = new \DateTime($request->request->get('date'));
        $type = $request->request->get('type');
        $nombrePersonnes = $request->request->get('nombrePersonnes');
        $repas = $repasRepository->find($id);

        if (!$repas) {
            $this->addFlash('error', 'Recette introuvable.');
            return $this->redirectToRoute('repas_show', ['id' => $id]);
        }

        // Vérifie s'il y a déjà un planning pour cette date
        $planning = $this->planningRepository->findOneByDate($date);

        if (!$planning) {
            $planning = new Planning();
            $planning->setDate($date);
        }

        // Ajoute le repas et le nombre de personnes en fonction du type sélectionné
        switch ($type) {
            case 'petitDejeuner':
                $planning->setPetitDejeuner($repas);
                $planning->setNombrePersonnesPetitDejeuner($nombrePersonnes);
                break;
            case 'encasMatin':
                $planning->setEncasMatin($repas);
                $planning->setNombrePersonnesEncasMatin($nombrePersonnes);
                break;
            case 'dejeuner':
                $planning->setDejeuner($repas);
                $planning->setNombrePersonnesDejeuner($nombrePersonnes);
                break;
            case 'encasApresMidi':
                $planning->setEncasApresMidi($repas);
                $planning->setNombrePersonnesEncasApresMidi($nombrePersonnes);
                break;
            case 'diner':
                $planning->setDiner($repas);
                $planning->setNombrePersonnesDiner($nombrePersonnes);
                break;
            default:
                $this->addFlash('error', 'Type de repas invalide.');
                return $this->redirectToRoute('repas_show', ['id' => $id]);
        }

        $this->entityManager->persist($planning);
        $this->entityManager->flush();

        $this->addFlash('success', 'Recette ajoutée au planning.');
        return $this->redirectToRoute('index');
    }

    public function findOneByDate(\DateTimeInterface $date): ?Planning
    {
        // Validation de la date
        if ($date < new \DateTime('2000-01-01') || $date > new \DateTime('+1 year')) {
            throw new \InvalidArgumentException('Date invalide');
        }
        try {
            return $this->planningRepository->createQueryBuilder('p')
                ->leftJoin('p.petitDejeuner', 'pd')
                ->leftJoin('p.encasMatin', 'em')
                ->leftJoin('p.dejeuner', 'd')
                ->leftJoin('p.encasApresMidi', 'ea')
                ->leftJoin('p.diner', 'di')
                ->addSelect('pd', 'em', 'd', 'ea', 'di')
                ->andWhere('p.date = :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->getQuery()
                ->getOneOrNullResult();
        } catch (\Exception $e) {
            // Log l'erreur
            throw new \RuntimeException('Erreur lors de la récupération du planning', 0, $e);
        }
    }
}