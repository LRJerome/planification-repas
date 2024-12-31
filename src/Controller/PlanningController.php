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
use Symfony\Component\HttpFoundation\JsonResponse;

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
            // Récupérer tous les plannings pour cette date
            $dateInfo = [
                'date' => clone $currentDate,
                'plannings' => $planningRepository->findByDateWithMeals($currentDate)
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
        try {
            $date = new \DateTime($request->request->get('date'));
            $type = $request->request->get('type');
            $nombrePersonnes = $request->request->get('nombrePersonnes');
            $force = $request->request->get('force') === 'true';
            $repas = $repasRepository->find($id);

            if (!$repas) {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(['error' => 'Recette introuvable.'], 404);
                }
                $this->addFlash('error', 'Recette introuvable.');
                return $this->redirectToRoute('repas_show', ['id' => $id]);
            }

            // Chercher tous les plannings existants pour cette date
            $existingPlannings = $this->planningRepository->findByDateWithMeals($date);
            
            $planning = null;
            if (!empty($existingPlannings)) {
                // Fusionner tous les plannings en un seul
                $planning = $existingPlannings[0];
                
                // Vérifier si le créneau est déjà occupé
                $existingMeal = match ($type) {
                    'petitDejeuner' => $planning->getPetitDejeuner(),
                    'encasMatin' => $planning->getEncasMatin(),
                    'dejeuner' => $planning->getDejeuner(),
                    'encasApresMidi' => $planning->getEncasApresMidi(),
                    'diner' => $planning->getDiner(),
                    default => null,
                };

                if ($existingMeal !== null && !$force) {
                    return new JsonResponse([
                        'needConfirmation' => true,
                        'message' => 'Un repas existe déjà pour ce créneau. Voulez-vous le remplacer ?'
                    ]);
                }

                // Supprimer les autres plannings après avoir fusionné leurs repas
                for ($i = 1; $i < count($existingPlannings); $i++) {
                    $otherPlanning = $existingPlannings[$i];
                    
                    // Fusionner les repas s'ils n'existent pas dans le planning principal
                    if (!$planning->getPetitDejeuner() && $otherPlanning->getPetitDejeuner()) {
                        $planning->setPetitDejeuner($otherPlanning->getPetitDejeuner());
                        $planning->setNombrePersonnesPetitDejeuner($otherPlanning->getNombrePersonnesPetitDejeuner());
                    }
                    if (!$planning->getEncasMatin() && $otherPlanning->getEncasMatin()) {
                        $planning->setEncasMatin($otherPlanning->getEncasMatin());
                        $planning->setNombrePersonnesEncasMatin($otherPlanning->getNombrePersonnesEncasMatin());
                    }
                    if (!$planning->getDejeuner() && $otherPlanning->getDejeuner()) {
                        $planning->setDejeuner($otherPlanning->getDejeuner());
                        $planning->setNombrePersonnesDejeuner($otherPlanning->getNombrePersonnesDejeuner());
                    }
                    if (!$planning->getEncasApresMidi() && $otherPlanning->getEncasApresMidi()) {
                        $planning->setEncasApresMidi($otherPlanning->getEncasApresMidi());
                        $planning->setNombrePersonnesEncasApresMidi($otherPlanning->getNombrePersonnesEncasApresMidi());
                    }
                    if (!$planning->getDiner() && $otherPlanning->getDiner()) {
                        $planning->setDiner($otherPlanning->getDiner());
                        $planning->setNombrePersonnesDiner($otherPlanning->getNombrePersonnesDiner());
                    }
                    
                    // Supprimer l'ancien planning
                    $this->entityManager->remove($otherPlanning);
                }
            }

            if (!$planning) {
                $planning = new Planning();
                $planning->setDate($date);
            }

            // Mettre à jour le planning avec le nouveau repas
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
                    throw new \Exception('Type de repas invalide');
            }

            $this->entityManager->persist($planning);
            $this->entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => true]);
            }

            $this->addFlash('success', 'Recette ajoutée au planning.');
            return $this->redirectToRoute('index');
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => $e->getMessage()], 500);
            }
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('repas_show', ['id' => $id]);
        }
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

    #[Route('/check-existing/{date}/{type}', name: 'check_existing', methods: ['GET'])]
    public function checkExisting(string $date, string $type): JsonResponse
    {
        $dateTime = new \DateTime($date);
        $planning = $this->planningRepository->findOneByDateWithMeals($dateTime);
        
        $exists = false;
        if ($planning) {
            $exists = match ($type) {
                'petitDejeuner' => $planning->getPetitDejeuner() !== null,
                'encasMatin' => $planning->getEncasMatin() !== null,
                'dejeuner' => $planning->getDejeuner() !== null,
                'encasApresMidi' => $planning->getEncasApresMidi() !== null,
                'diner' => $planning->getDiner() !== null,
                default => false,
            };
        }
        
        return $this->json(['exists' => $exists]);
    }
}