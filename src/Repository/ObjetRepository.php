<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Objet;
use App\Enum\StatutReparation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
    
class ObjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Objet::class);
    }

    public function findObjetsEnAttente(): array
    {
        // Récupérer tous les objets avec leurs réparations
        $objets = $this->createQueryBuilder('o')
            ->leftJoin('o.reparations', 'r')
            ->addSelect('r')
            ->orderBy('o.dateDepot', 'DESC')
            ->getQuery()
            ->getResult();

        // Filtrer en PHP pour trouver ceux en attente
        $objetsEnAttente = [];
        foreach ($objets as $objet) {
            $reparations = $objet->getReparations();
            
            // Si pas de réparation, l'objet est en attente
            if ($reparations->isEmpty()) {
                $objetsEnAttente[] = $objet;
                continue;
            }
            
            // Trouver la dernière réparation (celle avec l'ID le plus élevé)
            $derniereReparation = null;
            foreach ($reparations as $reparation) {
                if ($derniereReparation === null || $reparation->getId() > $derniereReparation->getId()) {
                    $derniereReparation = $reparation;
                }
            }
            
            // Si la dernière réparation est en attente
            if ($derniereReparation && $derniereReparation->getStatut() === StatutReparation::EN_ATTENTE) {
                $objetsEnAttente[] = $objet;
            }
        }

        return $objetsEnAttente;
    }

    /**
     * Trouve les objets par catégorie
     * 
     * @return Objet[]
     */
    public function findByCategorie(Category $categorie): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('o.dateDepot', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les objets par statut de réparation
     * Version simplifiée
     * 
     * @return array<string, int>
     */
    public function countByStatut(): array
    {
        // Récupérer tous les objets avec leurs réparations
        $objets = $this->createQueryBuilder('o')
            ->leftJoin('o.reparations', 'r')
            ->addSelect('r')
            ->getQuery()
            ->getResult();

        $stats = [];

        foreach ($objets as $objet) {
            $reparations = $objet->getReparations();
            
            // Si pas de réparation
            if ($reparations->isEmpty()) {
                $stats['Sans réparation'] = ($stats['Sans réparation'] ?? 0) + 1;
                continue;
            }
            
            // Trouver la dernière réparation
            $derniereReparation = null;
            foreach ($reparations as $reparation) {
                if ($derniereReparation === null || $reparation->getId() > $derniereReparation->getId()) {
                    $derniereReparation = $reparation;
                }
            }
            
            // Compter par statut
            if ($derniereReparation) {
                $statutValue = $derniereReparation->getStatut()->value;
                $stats[$statutValue] = ($stats[$statutValue] ?? 0) + 1;
            }
        }

        return $stats;
    }
}
