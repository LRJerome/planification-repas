<?php

namespace App\Command;

use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:cleanup-planning',
    description: 'Nettoie les plannings dupliqués'
)]
class CleanupPlanningCommand extends Command
{
    public function __construct(
        private PlanningRepository $planningRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $qb = $this->planningRepository->createQueryBuilder('p')
            ->select('p.date, COUNT(p.id) as cnt, MIN(p.id) as first_id')
            ->groupBy('p.date')
            ->having('cnt > 1');

        $duplicates = $qb->getQuery()->getResult();

        if (empty($duplicates)) {
            $io->success('Aucun planning dupliqué trouvé.');
            return Command::SUCCESS;
        }

        foreach ($duplicates as $duplicate) {
            $date = $duplicate['date'];
            
            // Récupérer tous les plannings pour cette date sauf le premier
            $planningsToRemove = $this->planningRepository->createQueryBuilder('p')
                ->where('p.date = :date')
                ->andWhere('p.id != :firstId')
                ->setParameter('date', $date)
                ->setParameter('firstId', $duplicate['first_id'])
                ->getQuery()
                ->getResult();

            foreach ($planningsToRemove as $planning) {
                $this->entityManager->remove($planning);
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('Nettoyage terminé. %d plannings dupliqués ont été supprimés.', count($duplicates)));

        return Command::SUCCESS;
    }
} 