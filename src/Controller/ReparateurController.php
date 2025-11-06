<?php

namespace App\Controller;

use App\Entity\Reparateur;
use App\Repository\ReparateurRepository;
use App\Enum\StatutReparation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reparateur')]
class ReparateurController extends AbstractController
{
    #[Route('s', name: 'app_reparateur_index', methods: ['GET'])]
    public function index(ReparateurRepository $reparateurRepository): Response
    {
        $reparateurs = $reparateurRepository->findActifsAvecNombreReparations();

        return $this->render('reparateur/index.html.twig', [
            'reparateurs' => $reparateurs,
        ]);
    }

    #[Route('/{id}', name: 'app_reparateur_show', methods: ['GET'])]
    public function show(Reparateur $reparateur): Response
    {
        // Calculer les statistiques personnelles
        $reparations = $reparateur->getReparations();
        $nombreTotal = $reparations->count();
        
        $nombreReparees = 0;
        $nombreIrreparables = 0;
        $tempsTotal = 0;
        $nombreAvecTemps = 0;
        
        foreach ($reparations as $reparation) {
            if ($reparation->getStatut() === StatutReparation::REPAREE) {
                $nombreReparees++;
                if ($reparation->getTempsPasseMinutes()) {
                    $tempsTotal += $reparation->getTempsPasseMinutes();
                    $nombreAvecTemps++;
                }
            } elseif ($reparation->getStatut() === StatutReparation::IRREPARABLE) {
                $nombreIrreparables++;
            }
        }
        
        $tauxReussite = $nombreTotal > 0 ? round(($nombreReparees / $nombreTotal) * 100, 1) : 0;
        $tempsMoyen = $nombreAvecTemps > 0 ? round($tempsTotal / $nombreAvecTemps) : 0;

        return $this->render('reparateur/show.html.twig', [
            'reparateur' => $reparateur,
            'nombreTotal' => $nombreTotal,
            'nombreReparees' => $nombreReparees,
            'nombreIrreparables' => $nombreIrreparables,
            'tauxReussite' => $tauxReussite,
            'tempsMoyen' => $tempsMoyen,
        ]);
    }
}

