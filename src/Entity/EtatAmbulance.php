<?php

namespace App\Entity;

use App\Repository\EtatAmbulanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatAmbulanceRepository::class)]
class EtatAmbulance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $etatAmbulance = null;

    #[ORM\OneToMany(targetEntity: Ambulance::class, mappedBy: 'etatAmbulance')]
    private Collection $ambulances;

    public function __construct()
    {
        $this->ambulances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtatAmbulance(): ?string
    {
        return $this->etatAmbulance;
    }

    public function setEtatAmbulance(string $etatAmbulance): static
    {
        $this->etatAmbulance = $etatAmbulance;

        return $this;
    }

    /**
     * @return Collection<int, Ambulance>
     */
    public function getAmbulances(): Collection
    {
        return $this->ambulances;
    }

    public function addAmbulance(Ambulance $ambulance): static
    {
        if (!$this->ambulances->contains($ambulance)) {
            $this->ambulances->add($ambulance);
            $ambulance->setEtatAmbulance($this);
        }

        return $this;
    }

    public function removeAmbulance(Ambulance $ambulance): static
    {
        if ($this->ambulances->removeElement($ambulance)) {
            // set the owning side to null (unless already changed)
            if ($ambulance->getEtatAmbulance() === $this) {
                $ambulance->setEtatAmbulance(null);
            }
        }

        return $this;
    }
}
