<?php

namespace App\Entity;

use App\Repository\TypeChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeChambreRepository::class)]
class TypeChambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeChambre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: Chambre::class, mappedBy: 'typeChambre')]
    private Collection $chambres;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prix = null;

    #[ORM\Column(nullable: true)]
    private ?bool $enService = null;

    #[ORM\Column(nullable: true)]
    private ?bool $supprime = null;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeChambre(): ?string
    {
        return $this->typeChambre;
    }

    public function setTypeChambre(?string $typeChambre): static
    {
        $this->typeChambre = $typeChambre;

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
     * @return Collection<int, Chambre>
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): static
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres->add($chambre);
            $chambre->setTypeChambre($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): static
    {
        if ($this->chambres->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getTypeChambre() === $this) {
                $chambre->setTypeChambre(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): static
    {
        $this->prix = $prix;

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

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(?bool $supprime): static
    {
        $this->supprime = $supprime;

        return $this;
    }
}
