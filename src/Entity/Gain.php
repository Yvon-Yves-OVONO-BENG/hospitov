<?php

namespace App\Entity;

use App\Repository\GainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GainRepository::class)]
class Gain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $gain = null;

    #[ORM\Column]
    private ?float $pourcentage = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: BulletinGain::class, mappedBy: 'gain')]
    private Collection $bulletinGains;

    public function __construct()
    {
        $this->bulletinGains = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGain(): ?string
    {
        return $this->gain;
    }

    public function setGain(string $gain): static
    {
        $this->gain = $gain;

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
     * @return Collection<int, BulletinGain>
     */
    public function getBulletinGains(): Collection
    {
        return $this->bulletinGains;
    }

    public function addBulletinGain(BulletinGain $bulletinGain): static
    {
        if (!$this->bulletinGains->contains($bulletinGain)) {
            $this->bulletinGains->add($bulletinGain);
            $bulletinGain->setGain($this);
        }

        return $this;
    }

    public function removeBulletinGain(BulletinGain $bulletinGain): static
    {
        if ($this->bulletinGains->removeElement($bulletinGain)) {
            // set the owning side to null (unless already changed)
            if ($bulletinGain->getGain() === $this) {
                $bulletinGain->setGain(null);
            }
        }

        return $this;
    }
}
