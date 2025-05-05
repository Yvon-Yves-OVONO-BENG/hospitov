<?php

namespace App\Entity;

use App\Repository\AttributionAmbulanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributionAmbulanceRepository::class)]
class AttributionAmbulance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attributionAmbulances')]
    private ?Ambulance $ambulance = null;

    #[ORM\ManyToOne(inversedBy: 'attributionAmbulances')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAttributionAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $retireAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmbulance(): ?Ambulance
    {
        return $this->ambulance;
    }

    public function setAmbulance(?Ambulance $ambulance): static
    {
        $this->ambulance = $ambulance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDateAttributionAt(): ?\DateTimeInterface
    {
        return $this->dateAttributionAt;
    }

    public function setDateAttributionAt(?\DateTimeInterface $dateAttributionAt): static
    {
        $this->dateAttributionAt = $dateAttributionAt;

        return $this;
    }

    public function getRetireAt(): ?\DateTimeInterface
    {
        return $this->retireAt;
    }

    public function setRetireAt(?\DateTimeInterface $retireAt): static
    {
        $this->retireAt = $retireAt;

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
}
