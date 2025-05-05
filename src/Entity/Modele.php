<?php

namespace App\Entity;

use App\Repository\ModeleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModeleRepository::class)]
class Modele
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modele = null;

    #[ORM\ManyToOne(inversedBy: 'modeles')]
    private ?Marque $marque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: Ambulance::class, mappedBy: 'modele')]
    private Collection $ambulances;

    #[ORM\Column(nullable: true)]
    private ?bool $supprime = null;

    #[ORM\Column(nullable: true)]
    private ?bool $visible = null;

    public function __construct()
    {
        $this->ambulances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): static
    {
        $this->marque = $marque;

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
            $ambulance->setModele($this);
        }

        return $this;
    }

    public function removeAmbulance(Ambulance $ambulance): static
    {
        if ($this->ambulances->removeElement($ambulance)) {
            // set the owning side to null (unless already changed)
            if ($ambulance->getModele() === $this) {
                $ambulance->setModele(null);
            }
        }

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

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }
}
