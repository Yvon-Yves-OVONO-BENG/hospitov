<?php

namespace App\Entity;

use App\Repository\PrimeSpecialeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrimeSpecialeRepository::class)]
class PrimeSpeciale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'primeSpeciales')]
    private ?User $personnel = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column]
    private ?int $mois = null;

    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAttributionAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonnel(): ?User
    {
        return $this->personnel;
    }

    public function setPersonnel(?User $personnel): static
    {
        $this->personnel = $personnel;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(int $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getDateAttributionAt(): ?\DateTimeInterface
    {
        return $this->dateAttributionAt;
    }

    public function setDateAttributionAt(\DateTimeInterface $dateAttributionAt): static
    {
        $this->dateAttributionAt = $dateAttributionAt;

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
}
