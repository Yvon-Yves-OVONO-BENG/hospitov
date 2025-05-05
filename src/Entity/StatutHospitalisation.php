<?php

namespace App\Entity;

use App\Repository\StatutHospitalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutHospitalisationRepository::class)]
class StatutHospitalisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $statutHospitalisation = null;

    #[ORM\Column]
    private ?bool $supprime = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: Hospitalisation::class, mappedBy: 'statut')]
    private Collection $hospitalisations;

    public function __construct()
    {
        $this->hospitalisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatutHospitalisation(): ?string
    {
        return $this->statutHospitalisation;
    }

    public function setStatutHospitalisation(string $statutHospitalisation): static
    {
        $this->statutHospitalisation = $statutHospitalisation;

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
            $hospitalisation->setStatut($this);
        }

        return $this;
    }

    public function removeHospitalisation(Hospitalisation $hospitalisation): static
    {
        if ($this->hospitalisations->removeElement($hospitalisation)) {
            // set the owning side to null (unless already changed)
            if ($hospitalisation->getStatut() === $this) {
                $hospitalisation->setStatut(null);
            }
        }

        return $this;
    }
}
