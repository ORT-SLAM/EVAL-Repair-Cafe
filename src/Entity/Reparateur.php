<?php

namespace App\Entity;

use App\Enum\NiveauExperience;
use App\Repository\ReparateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReparateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé')]
class Reparateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(
        min: 3,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide')]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/^0[1-9](-[0-9]{2}){4}$/',
        message: 'Le téléphone doit être au format 06-12-34-56-78'
    )]
    private ?string $telephone = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La date d\'inscription est obligatoire')]
    private ?\DateTimeImmutable $dateInscription = null;

    #[ORM\Column(type: 'string', enumType: NiveauExperience::class)]
    #[Assert\NotNull(message: 'Le niveau d\'expérience est obligatoire')]
    private ?NiveauExperience $niveauExperience = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $presentation = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le statut actif est obligatoire')]
    private ?bool $estActif = null;

    /**
     * @var Collection<int, Reparation>
     */
    #[ORM\OneToMany(targetEntity: Reparation::class, mappedBy: 'reparateur')]
    private Collection $reparations;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'reparateurs')]
    private Collection $specialites;

    public function __construct()
    {
        $this->reparations = new ArrayCollection();
        $this->specialites = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeImmutable
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeImmutable $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getNiveauExperience(): ?NiveauExperience
    {
        return $this->niveauExperience;
    }

    public function setNiveauExperience(NiveauExperience $niveauExperience): static
    {
        $this->niveauExperience = $niveauExperience;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): static
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function isEstActif(): ?bool
    {
        return $this->estActif;
    }

    public function setEstActif(bool $estActif): static
    {
        $this->estActif = $estActif;

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
            $reparation->setReparateur($this);
        }

        return $this;
    }

    public function removeReparation(Reparation $reparation): static
    {
        if ($this->reparations->removeElement($reparation)) {
            // set the owning side to null (unless already changed)
            if ($reparation->getReparateur() === $this) {
                $reparation->setReparateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getSpecialites(): Collection
    {
        return $this->specialites;
    }

    public function addSpecialite(Category $specialite): static
    {
        if (!$this->specialites->contains($specialite)) {
            $this->specialites->add($specialite);
        }

        return $this;
    }

    public function removeSpecialite(Category $specialite): static
    {
        $this->specialites->removeElement($specialite);

        return $this;
    }
}
