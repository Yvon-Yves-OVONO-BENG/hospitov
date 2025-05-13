<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chambre = null;

    #[ORM\ManyToOne(inversedBy: 'chambres')]
    private ?TypeChambre $typeChambre = null;

    #[ORM\ManyToOne(inversedBy: 'chambres')]
    private ?Niveau $niveau = null;

    #[ORM\ManyToOne(inversedBy: 'chambres')]
    private ?Batiment $batiment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'chambres')]
    private ?Specialite $specialite = null;

    #[ORM\Column(nullable: true)]
    private ?bool $enService = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreLit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $supprime = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreLitOccupe = null;

    #[ORM\OneToMany(targetEntity: Lit::class, mappedBy: 'chambre')]
    private Collection $lits;

    public function __construct()
    {
        $this->lits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChambre(): ?string
    {
        return $this->chambre;
    }

    public function setChambre(?string $chambre): static
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getTypeChambre(): ?TypeChambre
    {
        return $this->typeChambre;
    }

    public function setTypeChambre(?TypeChambre $typeChambre): static
    {
        $this->typeChambre = $typeChambre;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getBatiment(): ?Batiment
    {
        return $this->batiment;
    }

    public function setBatiment(?Batiment $batiment): static
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function isEnService(): ?bool
    {
        return $this->enService;
    }

    public function setEnService(?bool $enService): static
    {
        $this->enService = $enService;

        return $this;
    }

    public function getNombreLit(): ?int
    {
        return $this->nombreLit;
    }

    public function setNombreLit(?int $nombreLit): static
    {
        $this->nombreLit = $nombreLit;

        return $this;
    }

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(?bool $supprime): static
    {
        $this->supprime = $supprime;

        return $this;
    }

    public function getNombreLitOccupe(): ?int
    {
        return $this->nombreLitOccupe;
    }

    public function setNombreLitOccupe(?int $nombreLitOccupe): static
    {
        $this->nombreLitOccupe = $nombreLitOccupe;

        return $this;
    }

    /**
     * @return Collection<int, Lit>
     */
    public function getLits(): Collection
    {
        return $this->lits;
    }

    public function addLit(Lit $lit): static
    {
        if (!$this->lits->contains($lit)) {
            $this->lits->add($lit);
            $lit->setChambre($this);
        }

        return $this;
    }

    public function removeLit(Lit $lit): static
    {
        if ($this->lits->removeElement($lit)) {
            // set the owning side to null (unless already changed)
            if ($lit->getChambre() === $this) {
                $lit->setChambre(null);
            }
        }

        return $this;
    }

}
