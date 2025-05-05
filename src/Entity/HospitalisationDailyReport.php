<?php

namespace App\Entity;

use App\Repository\HospitalisationDailyReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HospitalisationDailyReportRepository::class)]
class HospitalisationDailyReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisationDailyReports')]
    private ?Hospitalisation $hospitalisation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(length: 255)]
    private ?string $tension = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $observation = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ordonnanceDuJour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHospitalisation(): ?Hospitalisation
    {
        return $this->hospitalisation;
    }

    public function setHospitalisation(?Hospitalisation $hospitalisation): static
    {
        $this->hospitalisation = $hospitalisation;

        return $this;
    }

    public function getDateAt(): ?\DateTimeInterface
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeInterface $dateAt): static
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(?string $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTension(): ?string
    {
        return $this->tension;
    }

    public function setTension(string $tension): static
    {
        $this->tension = $tension;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): static
    {
        $this->observation = $observation;

        return $this;
    }

    public function getOrdonnanceDuJour(): ?string
    {
        return $this->ordonnanceDuJour;
    }

    public function setOrdonnanceDuJour(string $ordonnanceDuJour): static
    {
        $this->ordonnanceDuJour = $ordonnanceDuJour;

        return $this;
    }
}
