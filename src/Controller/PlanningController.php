<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Psr\Log\LoggerInterface;

#[Route('/planning')]
class PlanningController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/', name: 'planning_index', methods: ['GET', 'POST'])]
    public function index(Request $request, PlanningRepository $planningRepository): Response
    {
        $today = new \DateTime();
        $endDate = (new \DateTime())->modify('+7 days');

        $form = $this->createFormBuilder()
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'data' => $today,
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'data' => $endDate,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Rechercher'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $dateDebut = $data['dateDebut'];
            $dateFin = $data['dateFin'];
        } else {
            $dateDebut = $today;
            $dateFin = $endDate;
        }

        // S'assurer que la date de début est bien le début de la journée
        $dateDebut->setTime(0, 0, 0);
        // S'assurer que la date de fin est bien la fin de la journée
        $dateFin->setTime(23, 59, 59);

        $this->logger->info('Recherche de plannings entre : ' . $dateDebut->format('Y-m-d H:i:s') . ' et ' . $dateFin->format('Y-m-d H:i:s'));

        $plannings = $planningRepository->findByDateRange($dateDebut, $dateFin);

        $this->logger->info('Nombre de plannings trouvés : ' . count($plannings));

        $planningsByDate = $this->organizePlanningsByDate($plannings, $dateDebut, $dateFin);

        return $this->render('planning/index.html.twig', [
            'planningsByDate' => $planningsByDate,
            'form' => $form->createView(),
        ]);
    }

    private function organizePlanningsByDate(array $plannings, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        $planningsByDate = [];
        $currentDate = clone $dateDebut;

        while ($currentDate <= $dateFin) {
            $dateKey = $currentDate->format('Y-m-d');
            $planningsByDate[$dateKey] = [
                'date' => clone $currentDate,
                'petitDejeuner' => null,
                'nombrePersonnesPetitDejeuner' => null,
                'encasMatin' => null,
                'nombrePersonnesEncasMatin' => null,
                'dejeuner' => null,
                'nombrePersonnesDejeuner' => null,
                'encasApresMidi' => null,
                'nombrePersonnesEncasApresMidi' => null,
                'diner' => null,
                'nombrePersonnesDiner' => null
            ];
            $currentDate->modify('+1 day');
        }

        foreach ($plannings as $planning) {
            $dateKey = $planning->getDate()->format('Y-m-d');
            if (isset($planningsByDate[$dateKey])) {
                $planningsByDate[$dateKey]['petitDejeuner'] = $planning->getPetitDejeuner();
                $planningsByDate[$dateKey]['nombrePersonnesPetitDejeuner'] = $planning->getNombrePersonnesPetitDejeuner();
                $planningsByDate[$dateKey]['encasMatin'] = $planning->getEncasMatin();
                $planningsByDate[$dateKey]['nombrePersonnesEncasMatin'] = $planning->getNombrePersonnesEncasMatin();
                $planningsByDate[$dateKey]['dejeuner'] = $planning->getDejeuner();
                $planningsByDate[$dateKey]['nombrePersonnesDejeuner'] = $planning->getNombrePersonnesDejeuner();
                $planningsByDate[$dateKey]['encasApresMidi'] = $planning->getEncasApresMidi();
                $planningsByDate[$dateKey]['nombrePersonnesEncasApresMidi'] = $planning->getNombrePersonnesEncasApresMidi();
                $planningsByDate[$dateKey]['diner'] = $planning->getDiner();
                $planningsByDate[$dateKey]['nombrePersonnesDiner'] = $planning->getNombrePersonnesDiner();
            }
        }

        return $planningsByDate;
    }

    #[Route('/new', name: 'planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($planning);
            $entityManager->flush();

            $this->addFlash('success', 'Le planning a été créé avec succès.');
            return $this->redirectToRoute('planning_index');
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/{id}/edit', name: 'planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le planning a été mis à jour avec succès.');
            return $this->redirectToRoute('planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $entityManager->remove($planning);
            $entityManager->flush();

            $this->addFlash('success', 'Le planning a été supprimé avec succès.');
        }

        return $this->redirectToRoute('planning_index', [], Response::HTTP_SEE_OTHER);
    }
}