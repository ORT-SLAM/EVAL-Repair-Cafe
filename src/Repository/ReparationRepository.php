<?php

namespace App\Repository;

use App\Entity\Reparation;
use App\Enum\StatutReparation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReparationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reparation::class);
    }

    public function rechercherReparations(array $criteres = []): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.objet', 'o')->addSelect('o')
            ->leftJoin('o.categorie', 'c')->addSelect('c')
            ->leftJoin('r.reparateur', 'rep')->addSelect('rep');

        // Appliquer les filtres s'ils sont définis
        if (!empty($criteres['statut'])) {
            $qb->andWhere('r.statut = :statut')
               ->setParameter('statut', $criteres['statut']);
        }

        if (!empty($criteres['categorieId'])) {
            $qb->andWhere('c.id = :categorieId')
               ->setParameter('categorieId', $criteres['categorieId']);
        }

        if (!empty($criteres['reparateurId'])) {
            $qb->andWhere('rep.id = :reparateurId')
               ->setParameter('reparateurId', $criteres['reparateurId']);
        }

        if (!empty($criteres['dateDebut'])) {
            $qb->andWhere('r.dateDebut >= :dateDebut')
               ->setParameter('dateDebut', $criteres['dateDebut']);
        }

        if (!empty($criteres['dateFin'])) {
            $qb->andWhere('r.dateDebut <= :dateFin')
               ->setParameter('dateFin', $criteres['dateFin']);
        }

        return $qb->orderBy('r.dateDebut', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    public function getStatistiquesParMois(int $annee): array
    {
        // Récupérer toutes les réparations de l'année
        $reparations = $this->createQueryBuilder('r')
            ->where('YEAR(r.dateDebut) = :annee')
            ->setParameter('annee', $annee)
            ->getQuery()
            ->getResult();

        // Initialiser le tableau avec 0 pour chaque mois
        $stats = array_fill(1, 12, 0);

        // Compter les réparations par mois
        foreach ($reparations as $reparation) {
            $mois = (int) $reparation->getDateDebut()->format('n');
            $stats[$mois]++;
        }

        return $stats;
    }
}
