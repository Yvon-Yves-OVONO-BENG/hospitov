<?php

namespace App\Entity;

use App\Repository\HospitalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HospitalisationRepository::class)]
class Hospitalisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Chambre $chambre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEntreeAt = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureEntreeAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSortieAt = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureSortieAt = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Consultation $consultationInitiale = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?StatutHospitalisation $statut = null;

    #[ORM\OneToMany(targetEntity: HospitalisationDailyReport::class, mappedBy: 'hospitalisation')]
    private Collection $hospitalisationDailyReports;

    public function __construct()
    {
        $this->hospitalisationDailyReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): static
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getDateEntreeAt(): ?\DateTimeInterface
    {
        return $this->dateEntreeAt;
    }

    public function setDateEntreeAt(?\DateTimeInterface $dateEntreeAt): static
    {
        $this->dateEntreeAt = $dateEntreeAt;

        return $this;
    }

    public function getHeureEntreeAt(): ?\DateTimeInterface
    {
        return $this->heureEntreeAt;
    }

    public function setHeureEntreeAt(?\DateTimeInterface $heureEntreeAt): static
    {
        $this->heureEntreeAt = $heureEntreeAt;

        return $this;
    }

    public function getDateSortieAt(): ?\DateTimeInterface
    {
        return $this->dateSortieAt;
    }

    public function setDateSortieAt(?\DateTimeInterface $dateSortieAt): static
    {
        $this->dateSortieAt = $dateSortieAt;

        return $this;
    }

    public function getHeureSortieAt(): ?\DateTimeInterface
    {
        return $this->heureSortieAt;
    }

    public function setHeureSortieAt(?\DateTimeInterface $heureSortieAt): static
    {
        $this->heureSortieAt = $heureSortieAt;

        return $this;
    }

    public function getConsultationInitiale(): ?Consultation
    {
        return $this->consultationInitiale;
    }

    public function setConsultationInitiale(?Consultation $consultationInitiale): static
    {
        $this->consultationInitiale = $consultationInitiale;

        return $this;
    }

    public function getStatut(): ?StatutHospitalisation
    {
        return $this->statut;
    }

    public function setStatut(?StatutHospitalisation $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, HospitalisationDailyReport>
     */
    public function getHospitalisationDailyReports(): Collection
    {
        return $this->hospitalisationDailyReports;
    }

    public function addHospitalisationDailyReport(HospitalisationDailyReport $hospitalisationDailyReport): static
    {
        if (!$this->hospitalisationDailyReports->contains($hospitalisationDailyReport)) {
            $this->hospitalisationDailyReports->add($hospitalisationDailyReport);
            $hospitalisationDailyReport->setHospitalisation($this);
        }

        return $this;
    }

    public function removeHospitalisationDailyReport(HospitalisationDailyReport $hospitalisationDailyReport): static
    {
        if ($this->hospitalisationDailyReports->removeElement($hospitalisationDailyReport)) {
            // set the owning side to null (unless already changed)
            if ($hospitalisationDailyReport->getHospitalisation() === $this) {
                $hospitalisationDailyReport->setHospitalisation(null);
            }
        }

        return $this;
    }
}
