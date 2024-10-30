<?php

namespace App\Controller;

use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function allPlannings(PlanningRepository $planningRepository): Response
    {
        // Récupérer tous les plannings sans filtre de date
        $plannings = $planningRepository->findAll();

        // Organiser les plannings par date
        $planningsByDate = [];
        foreach ($plannings as $planning) {
            $dateKey = $planning->getDate()->format('Y-m-d');
            if (!isset($planningsByDate[$dateKey])) {
                $planningsByDate[$dateKey] = [
                    'date' => $planning->getDate(),
                    'petitDejeuner' => $planning->getPetitDejeuner(),
                    'nombrePersonnesPetitDejeuner' => $planning->getNombrePersonnesPetitDejeuner(),
                    'encasMatin' => $planning->getEncasMatin(),
                    'nombrePersonnesEncasMatin' => $planning->getNombrePersonnesEncasMatin(),
                    'dejeuner' => $planning->getDejeuner(),
                    'nombrePersonnesDejeuner' => $planning->getNombrePersonnesDejeuner(),
                    'encasApresMidi' => $planning->getEncasApresMidi(),
                    'nombrePersonnesEncasApresMidi' => $planning->getNombrePersonnesEncasApresMidi(),
                    'diner' => $planning->getDiner(),
                    'nombrePersonnesDiner' => $planning->getNombrePersonnesDiner()
                ];
            } else {
                // Mettre à jour les repas s'ils sont définis dans ce planning
                if ($planning->getPetitDejeuner()) {
                    $planningsByDate[$dateKey]['petitDejeuner'] = $planning->getPetitDejeuner();
                    $planningsByDate[$dateKey]['nombrePersonnesPetitDejeuner'] = $planning->getNombrePersonnesPetitDejeuner();
                }
                if ($planning->getEncasMatin()) {
                    $planningsByDate[$dateKey]['encasMatin'] = $planning->getEncasMatin();
                    $planningsByDate[$dateKey]['nombrePersonnesEncasMatin'] = $planning->getNombrePersonnesEncasMatin();
                }
                if ($planning->getDejeuner()) {
                    $planningsByDate[$dateKey]['dejeuner'] = $planning->getDejeuner();
                    $planningsByDate[$dateKey]['nombrePersonnesDejeuner'] = $planning->getNombrePersonnesDejeuner();
                }
                if ($planning->getEncasApresMidi()) {
                    $planningsByDate[$dateKey]['encasApresMidi'] = $planning->getEncasApresMidi();
                    $planningsByDate[$dateKey]['nombrePersonnesEncasApresMidi'] = $planning->getNombrePersonnesEncasApresMidi();
                }
                if ($planning->getDiner()) {
                    $planningsByDate[$dateKey]['diner'] = $planning->getDiner();
                    $planningsByDate[$dateKey]['nombrePersonnesDiner'] = $planning->getNombrePersonnesDiner();
                }
            }
        }

        // Trier les plannings par date (du plus récent au plus ancien)
        krsort($planningsByDate);

        return $this->render('home/index.html.twig', [
            'planningsByDate' => $planningsByDate,
        ]);
    }
}