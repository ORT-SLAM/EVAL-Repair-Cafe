<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de la catégorie est obligatoire')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La couleur est obligatoire')]
    #[Assert\Regex(
        pattern: '/^#[0-9A-Fa-f]{6}$/',
        message: 'La couleur doit être au format hexadécimal (#RRGGBB)'
    )]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'icône est obligatoire')]
    private ?string $icone = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description est obligatoire')]
    private ?string $description = null;

    /**
     * @var Collection<int, Objet>
     */
    #[ORM\OneToMany(targetEntity: Objet::class, mappedBy: 'categorie')]
    private Collection $objets;

    /**
     * @var Collection<int, Reparateur>
     */
    #[ORM\ManyToMany(targetEntity: Reparateur::class, mappedBy: 'specialites')]
    private Collection $reparateurs;

    public function __construct()
    {
        $this->objets = new ArrayCollection();
        $this->reparateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(string $icone): static
    {
        $this->icone = $icone;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Objet>
     */
    public function getObjets(): Collection
    {
        return $this->objets;
    }

    public function addObjet(Objet $objet): static
    {
        if (!$this->objets->contains($objet)) {
            $this->objets->add($objet);
            $objet->setCategorie($this);
        }

        return $this;
    }

    public function removeObjet(Objet $objet): static
    {
        if ($this->objets->removeElement($objet)) {
            // set the owning side to null (unless already changed)
            if ($objet->getCategorie() === $this) {
                $objet->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reparateur>
     */
    public function getReparateurs(): Collection
    {
        return $this->reparateurs;
    }

    public function addReparateur(Reparateur $reparateur): static
    {
        if (!$this->reparateurs->contains($reparateur)) {
            $this->reparateurs->add($reparateur);
            $reparateur->addSpecialite($this);
        }

        return $this;
    }

    public function removeReparateur(Reparateur $reparateur): static
    {
        if ($this->reparateurs->removeElement($reparateur)) {
            $reparateur->removeSpecialite($this);
        }

        return $this;
    }
}
