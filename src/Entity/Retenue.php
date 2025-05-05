<?php

namespace App\Entity;

use App\Repository\RetenueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetenueRepository::class)]
class Retenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $retenue = null;

    #[ORM\Column]
    private ?float $pourcentage = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: BulletinRetenue::class, mappedBy: 'retenue')]
    private Collection $bulletinRetenues;

    public function __construct()
    {
        $this->bulletinRetenues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRetenue(): ?string
    {
        return $this->retenue;
    }

    public function setRetenue(string $retenue): static
    {
        $this->retenue = $retenue;

        return $this;
    }

    public function getPourcentage(): ?float
    {
        return $this->pourcentage;
    }

    public function setPourcentage(float $pourcentage): static
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

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
     * @return Collection<int, BulletinRetenue>
     */
    public function getBulletinRetenues(): Collection
    {
        return $this->bulletinRetenues;
    }

    public function addBulletinRetenue(BulletinRetenue $bulletinRetenue): static
    {
        if (!$this->bulletinRetenues->contains($bulletinRetenue)) {
            $this->bulletinRetenues->add($bulletinRetenue);
            $bulletinRetenue->setRetenue($this);
        }

        return $this;
    }

    public function removeBulletinRetenue(BulletinRetenue $bulletinRetenue): static
    {
        if ($this->bulletinRetenues->removeElement($bulletinRetenue)) {
            // set the owning side to null (unless already changed)
            if ($bulletinRetenue->getRetenue() === $this) {
                $bulletinRetenue->setRetenue(null);
            }
        }

        return $this;
    }
}
