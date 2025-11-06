<?php

namespace App\Entity;

use App\Repository\ObjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ObjetRepository::class)]
class Objet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(
        min: 5,
        max: 200,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description de la panne est obligatoire')]
    private ?string $descriptionPanne = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du propriétaire est obligatoire')]
    private ?string $nomProprietaire = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'email du propriétaire est obligatoire')]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide')]
    private ?string $emailProprietaire = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La date de dépôt est obligatoire')]
    private ?string $dateDepot = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $estimationCoutReparation = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le statut fonctionnel est obligatoire')]
    private ?bool $estFonctionnel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    /**
     * @var Collection<int, Reparation>
     */
    #[ORM\OneToMany(targetEntity: Reparation::class, mappedBy: 'objet')]
    private Collection $reparations;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'La catégorie est obligatoire')]
    private ?Category $categorie = null;

    public function __construct()
    {
        $this->reparations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptionPanne(): ?string
    {
        return $this->descriptionPanne;
    }

    public function setDescriptionPanne(string $descriptionPanne): static
    {
        $this->descriptionPanne = $descriptionPanne;

        return $this;
    }

    public function getNomProprietaire(): ?string
    {
        return $this->nomProprietaire;
    }

    public function setNomProprietaire(string $nomProprietaire): static
    {
        $this->nomProprietaire = $nomProprietaire;

        return $this;
    }

    public function getEmailProprietaire(): ?string
    {
        return $this->emailProprietaire;
    }

    public function setEmailProprietaire(string $emailProprietaire): static
    {
        $this->emailProprietaire = $emailProprietaire;

        return $this;
    }

    public function getDateDepot(): ?string
    {
        return $this->dateDepot;
    }

    public function setDateDepot(string $dateDepot): static
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getEstimationCoutReparation(): ?string
    {
        return $this->estimationCoutReparation;
    }

    public function setEstimationCoutReparation(?string $estimationCoutReparation): static
    {
        $this->estimationCoutReparation = $estimationCoutReparation;

        return $this;
    }

    public function isEstFonctionnel(): ?bool
    {
        return $this->estFonctionnel;
    }

    public function setEstFonctionnel(bool $estFonctionnel): static
    {
        $this->estFonctionnel = $estFonctionnel;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Reparation>
     */
    public function getReparations(): Collection
    {
        return $this->reparations;
    }

    public function addReparation(Reparation $reparation): static
    {
        if (!$this->reparations->contains($reparation)) {
            $this->reparations->add($reparation);
            $reparation->setObjet($this);
        }

        return $this;
    }

    public function removeReparation(Reparation $reparation): static
    {
        if ($this->reparations->removeElement($reparation)) {
            // set the owning side to null (unless already changed)
            if ($reparation->getObjet() === $this) {
                $reparation->setObjet(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Category
    {
        return $this->categorie;
    }

    public function setCategorie(?Category $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
