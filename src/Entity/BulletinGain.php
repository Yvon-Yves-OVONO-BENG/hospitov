<?php

namespace App\Entity;

use App\Repository\BulletinGainRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BulletinGainRepository::class)]
class BulletinGain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gains')]
    private ?BulletinSalaire $bulletinSalaire = null;

    #[ORM\ManyToOne(inversedBy: 'bulletinGains')]
    private ?Gain $gain = null;

    #[ORM\Column]
    private ?float $montant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBulletinSalaire(): ?BulletinSalaire
    {
        return $this->bulletinSalaire;
    }

    public function setBulletinSalaire(?BulletinSalaire $bulletinSalaire): static
    {
        $this->bulletinSalaire = $bulletinSalaire;

        return $this;
    }

    public function getGain(): ?Gain
    {
        return $this->gain;
    }

    public function setGain(?Gain $gain): static
    {
        $this->gain = $gain;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }
}
