<?php

namespace App\Repository;

use App\Entity\Reparateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ReparateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reparateur::class);
    }

    public function findActifsAvecNombreReparations(): array
    {
        // Récupérer les réparateurs actifs avec leurs réparations
        $reparateurs = $this->createQueryBuilder('r')
            ->leftJoin('r.reparations', 'rep')
            ->addSelect('rep')
            ->where('r.estActif = true')
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();

        // Compter les réparations pour chaque réparateur
        $resultat = [];
        foreach ($reparateurs as $reparateur) {
            $resultat[] = [
                'reparateur' => $reparateur,
                'nombreReparations' => $reparateur->getReparations()->count()
            ];
        }

        return $resultat;
    }

    public function findTopReparateurs(int $limit = 5): array
    {
        // Récupérer tous les réparateurs avec leurs réparations
        $reparateurs = $this->createQueryBuilder('r')
            ->leftJoin('r.reparations', 'rep')
            ->addSelect('rep')
            ->getQuery()
            ->getResult();

        // Créer un tableau avec le nombre de réparations
        $resultat = [];
        foreach ($reparateurs as $reparateur) {
            $resultat[] = [
                'reparateur' => $reparateur,
                'nombreReparations' => $reparateur->getReparations()->count()
            ];
        }

        // Trier par nombre de réparations décroissant
        usort($resultat, function($a, $b) {
            return $b['nombreReparations'] - $a['nombreReparations'];
        });

        // Retourner seulement les X premiers
        return array_slice($resultat, 0, $limit);
    }
}
