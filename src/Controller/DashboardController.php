<?php

namespace App\Controller;

use App\Repository\ObjetRepository;
use App\Repository\ReparateurRepository;
use App\Repository\ReparationRepository;
use App\Enum\StatutReparation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        ObjetRepository $objetRepository,
        ReparateurRepository $reparateurRepository,
        ReparationRepository $reparationRepository
    ): Response {
        // Statistiques
        $statsStatuts = $objetRepository->countByStatut();
        $nombreObjetsTotal = array_sum($statsStatuts);
        $nombreObjetsRepares = $statsStatuts[StatutReparation::REPAREE->value] ?? 0;
        $nombreObjetsIrreparables = $statsStatuts[StatutReparation::IRREPARABLE->value] ?? 0;
        
        // Réparateurs actifs
        $reparateursActifs = $reparateurRepository->findBy(['estActif' => true]);
        $nombreReparateursActifs = count($reparateursActifs);
        
        // 5 dernières réparations
        $dernieresReparations = $reparationRepository->findBy(
            [],
            ['dateDebut' => 'DESC'],
            5
        );

        return $this->render('dashboard/index.html.twig', [
            'nombreObjetsTotal' => $nombreObjetsTotal,
            'nombreObjetsRepares' => $nombreObjetsRepares,
            'nombreObjetsIrreparables' => $nombreObjetsIrreparables,
            'nombreReparateursActifs' => $nombreReparateursActifs,
            'statsStatuts' => $statsStatuts,
            'dernieresReparations' => $dernieresReparations,
        ]);
    }
}

