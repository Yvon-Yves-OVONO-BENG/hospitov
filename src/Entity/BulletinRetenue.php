<?php

namespace App\Entity;

use App\Repository\BulletinRetenueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BulletinRetenueRepository::class)]
class BulletinRetenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'retenues')]
    private ?BulletinSalaire $bulletinSalaire = null;

    #[ORM\ManyToOne(inversedBy: 'bulletinRetenues')]
    private ?Retenue $retenue = null;

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

    public function getRetenue(): ?Retenue
    {
        return $this->retenue;
    }

    public function setRetenue(?Retenue $retenue): static
    {
        $this->retenue = $retenue;

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
