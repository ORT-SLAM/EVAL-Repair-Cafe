<?php

namespace App\Entity;

use App\Enum\StatutReparation;
use App\Repository\ReparationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReparationRepository::class)]
class Reparation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column(type: 'string', enumType: StatutReparation::class)]
    private ?StatutReparation $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(nullable: true)]
    private ?int $tempsPasseMinutes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $piecesUtilisees = null;

    #[ORM\ManyToOne(inversedBy: 'reparations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Objet $objet = null;

    #[ORM\ManyToOne(inversedBy: 'reparations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reparateur $reparateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getStatut(): ?StatutReparation
    {
        return $this->statut;
    }

    public function setStatut(StatutReparation $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getTempsPasseMinutes(): ?int
    {
        return $this->tempsPasseMinutes;
    }

    public function setTempsPasseMinutes(?int $tempsPasseMinutes): static
    {
        $this->tempsPasseMinutes = $tempsPasseMinutes;

        return $this;
    }

    public function getPiecesUtilisees(): ?string
    {
        return $this->piecesUtilisees;
    }

    public function setPiecesUtilisees(?string $piecesUtilisees): static
    {
        $this->piecesUtilisees = $piecesUtilisees;

        return $this;
    }

    public function getObjet(): ?Objet
    {
        return $this->objet;
    }

    public function setObjet(?Objet $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function getReparateur(): ?Reparateur
    {
        return $this->reparateur;
    }

    public function setReparateur(?Reparateur $reparateur): static
    {
        $this->reparateur = $reparateur;

        return $this;
    }
}
