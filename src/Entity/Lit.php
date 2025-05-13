<?php

namespace App\Entity;

use App\Repository\LitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LitRepository::class)]
class Lit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroLit = null;

    #[ORM\ManyToOne(inversedBy: 'lits')]
    private ?Chambre $chambre = null;

    #[ORM\Column]
    private ?bool $supprime = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $disponible = null;

    #[ORM\Column]
    private ?bool $enService = null;

    #[ORM\OneToMany(targetEntity: Hospitalisation::class, mappedBy: 'lit')]
    private Collection $hospitalisations;

    public function __construct()
    {
        $this->hospitalisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroLit(): ?string
    {
        return $this->numeroLit;
    }

    public function setNumeroLit(string $numeroLit): static
    {
        $this->numeroLit = $numeroLit;

        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): static
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(bool $supprime): static
    {
        $this->supprime = $supprime;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function isEnService(): ?bool
    {
        return $this->enService;
    }

    public function setEnService(bool $enService): static
    {
        $this->enService = $enService;

        return $this;
    }

    /**
     * @return Collection<int, Hospitalisation>
     */
    public function getHospitalisations(): Collection
    {
        return $this->hospitalisations;
    }

    public function addHospitalisation(Hospitalisation $hospitalisation): static
    {
        if (!$this->hospitalisations->contains($hospitalisation)) {
            $this->hospitalisations->add($hospitalisation);
            $hospitalisation->setLit($this);
        }

        return $this;
    }

    public function removeHospitalisation(Hospitalisation $hospitalisation): static
    {
        if ($this->hospitalisations->removeElement($hospitalisation)) {
            // set the owning side to null (unless already changed)
            if ($hospitalisation->getLit() === $this) {
                $hospitalisation->setLit(null);
            }
        }

        return $this;
    }
}
