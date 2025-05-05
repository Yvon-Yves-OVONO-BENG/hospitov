<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateConsultationAt = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureConsultationAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $diagnostic = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $supprime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $symptomes = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?ParametresVitaux $parametresVitaux = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?User $medecin = null;

    #[ORM\OneToMany(targetEntity: Hospitalisation::class, mappedBy: 'consultationInitiale')]
    private Collection $hospitalisations;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $medicaments = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $examens = null;

    public function __construct()
    {
        $this->hospitalisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateConsultationAt(): ?\DateTimeInterface
    {
        return $this->dateConsultationAt;
    }

    public function setDateConsultationAt(?\DateTimeInterface $dateConsultationAt): static
    {
        $this->dateConsultationAt = $dateConsultationAt;

        return $this;
    }

    public function getHeureConsultationAt(): ?\DateTimeInterface
    {
        return $this->heureConsultationAt;
    }

    public function setHeureConsultationAt(?\DateTimeInterface $heureConsultationAt): static
    {
        $this->heureConsultationAt = $heureConsultationAt;

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

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(?bool $supprime): static
    {
        $this->supprime = $supprime;

        return $this;
    }

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(?string $diagnostic): static
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getSymptomes(): ?string
    {
        return $this->symptomes;
    }

    public function setSymptomes(string $symptomes): static
    {
        $this->symptomes = $symptomes;

        return $this;
    }

    public function getParametresVitaux(): ?ParametresVitaux
    {
        return $this->parametresVitaux;
    }

    public function setParametresVitaux(?ParametresVitaux $parametresVitaux): static
    {
        $this->parametresVitaux = $parametresVitaux;

        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): static
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * @return Collection<int, Hospitalisation>
     */
    public function getHospitalisations(): Collection
    {
        return $this->hospitalisations;
    }

    public function addHospitalisation(Hospitalisation $hospitalisation): static
    {
        if (!$this->hospitalisations->contains($hospitalisation)) {
            $this->hospitalisations->add($hospitalisation);
            $hospitalisation->setConsultationInitiale($this);
        }

        return $this;
    }

    public function removeHospitalisation(Hospitalisation $hospitalisation): static
    {
        if ($this->hospitalisations->removeElement($hospitalisation)) {
            // set the owning side to null (unless already changed)
            if ($hospitalisation->getConsultationInitiale() === $this) {
                $hospitalisation->setConsultationInitiale(null);
            }
        }

        return $this;
    }

    public function getMedicaments(): ?string
    {
        return $this->medicaments;
    }

    public function setMedicaments(?string $medicaments): static
    {
        $this->medicaments = $medicaments;

        return $this;
    }

    public function getExamens(): ?string
    {
        return $this->examens;
    }

    public function setExamens(?string $examens): static
    {
        $this->examens = $examens;

        return $this;
    }

}
