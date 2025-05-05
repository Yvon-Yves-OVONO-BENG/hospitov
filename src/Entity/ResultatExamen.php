<?php

namespace App\Entity;

use App\Repository\ResultatExamenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ResultatExamenRepository::class)]
class ResultatExamen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateResultatAt = null;

    #[ORM\ManyToOne(inversedBy: 'resultatExamens')]
    private ?User $laborantin = null;

    #[ORM\Column]
    private ?bool $realise = null;

    #[ORM\ManyToOne(inversedBy: 'resultatExamens')]
    private ?BilletDeSession $billetDeSession = null;

    #[ORM\Column]
    private ?bool $paye = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePrescriptionAt = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureResultatAt = null;

    #[ORM\ManyToOne(inversedBy: 'resultatExamens')]
    private ?Patient $patient = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: ListeExamensAFaire::class, mappedBy: 'resultatExamen')]
    private Collection $listeExamensAFaires;

    #[ORM\OneToMany(targetEntity: FichierResultatExamen::class, mappedBy: 'resultatExamen')]
    private Collection $fichierResultatExamens;

    public function __construct()
    {
        $this->listeExamensAFaires = new ArrayCollection();
        $this->fichierResultatExamens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateResultatAt(): ?\DateTimeInterface
    {
        return $this->dateResultatAt;
    }

    public function setDateResultatAt(\DateTimeInterface $dateResultatAt): static
    {
        $this->dateResultatAt = $dateResultatAt;

        return $this;
    }

    public function getLaborantin(): ?User
    {
        return $this->laborantin;
    }

    public function setLaborantin(?User $laborantin): static
    {
        $this->laborantin = $laborantin;

        return $this;
    }

    public function isRealise(): ?bool
    {
        return $this->realise;
    }

    public function setRealise(bool $realise): static
    {
        $this->realise = $realise;

        return $this;
    }

    public function getBilletDeSession(): ?BilletDeSession
    {
        return $this->billetDeSession;
    }

    public function setBilletDeSession(?BilletDeSession $billetDeSession): static
    {
        $this->billetDeSession = $billetDeSession;

        return $this;
    }

    public function isPaye(): ?bool
    {
        return $this->paye;
    }

    public function setPaye(bool $paye): static
    {
        $this->paye = $paye;

        return $this;
    }

    public function getDatePrescriptionAt(): ?\DateTimeInterface
    {
        return $this->datePrescriptionAt;
    }

    public function setDatePrescriptionAt(\DateTimeInterface $datePrescriptionAt): static
    {
        $this->datePrescriptionAt = $datePrescriptionAt;

        return $this;
    }

    public function getHeureResultatAt(): ?\DateTimeInterface
    {
        return $this->heureResultatAt;
    }

    public function setHeureResultatAt(?\DateTimeInterface $heureResultatAt): static
    {
        $this->heureResultatAt = $heureResultatAt;

        return $this;
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
     * @return Collection<int, ListeExamensAFaire>
     */
    public function getListeExamensAFaires(): Collection
    {
        return $this->listeExamensAFaires;
    }

    public function addListeExamensAFaire(ListeExamensAFaire $listeExamensAFaire): static
    {
        if (!$this->listeExamensAFaires->contains($listeExamensAFaire)) {
            $this->listeExamensAFaires->add($listeExamensAFaire);
            $listeExamensAFaire->setResultatExamen($this);
        }

        return $this;
    }

    public function removeListeExamensAFaire(ListeExamensAFaire $listeExamensAFaire): static
    {
        if ($this->listeExamensAFaires->removeElement($listeExamensAFaire)) {
            // set the owning side to null (unless already changed)
            if ($listeExamensAFaire->getResultatExamen() === $this) {
                $listeExamensAFaire->setResultatExamen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FichierResultatExamen>
     */
    public function getFichierResultatExamens(): Collection
    {
        return $this->fichierResultatExamens;
    }

    public function addFichierResultatExamen(FichierResultatExamen $fichierResultatExamen): static
    {
        if (!$this->fichierResultatExamens->contains($fichierResultatExamen)) {
            $this->fichierResultatExamens->add($fichierResultatExamen);
            $fichierResultatExamen->setResultatExamen($this);
        }

        return $this;
    }

    public function removeFichierResultatExamen(FichierResultatExamen $fichierResultatExamen): static
    {
        if ($this->fichierResultatExamens->removeElement($fichierResultatExamen)) {
            // set the owning side to null (unless already changed)
            if ($fichierResultatExamen->getResultatExamen() === $this) {
                $fichierResultatExamen->setResultatExamen(null);
            }
        }

        return $this;
    }
}
