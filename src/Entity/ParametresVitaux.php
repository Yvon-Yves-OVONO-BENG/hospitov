<?php

namespace App\Entity;

use App\Repository\ParametresVitauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParametresVitauxRepository::class)]
class ParametresVitaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $poids = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column(length: 255)]
    private ?string $tension = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePriseAt = null;

    #[ORM\ManyToOne(inversedBy: 'parametresVitauxes')]
    private ?User $infirmier = null;

    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'parametresVitaux')]
    private Collection $consultations;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'parametresVitauxes')]
    private ?BilletDeSession $billetDeSession = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heurePriseAt = null;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): static
    {
        $this->poids = $poids;

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

    public function getTension(): ?string
    {
        return $this->tension;
    }

    public function setTension(string $tension): static
    {
        $this->tension = $tension;

        return $this;
    }

    public function getDatePriseAt(): ?\DateTimeInterface
    {
        return $this->datePriseAt;
    }

    public function setDatePriseAt(\DateTimeInterface $datePriseAt): static
    {
        $this->datePriseAt = $datePriseAt;

        return $this;
    }


    public function getInfirmier(): ?User
    {
        return $this->infirmier;
    }

    public function setInfirmier(?User $infirmier): static
    {
        $this->infirmier = $infirmier;

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setParametresVitaux($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getParametresVitaux() === $this) {
                $consultation->setParametresVitaux(null);
            }
        }

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

    public function getBilletDeSession(): ?BilletDeSession
    {
        return $this->billetDeSession;
    }

    public function setBilletDeSession(?BilletDeSession $billetDeSession): static
    {
        $this->billetDeSession = $billetDeSession;

        return $this;
    }

    public function getHeurePriseAt(): ?\DateTimeInterface
    {
        return $this->heurePriseAt;
    }

    public function setHeurePriseAt(\DateTimeInterface $heurePriseAt): static
    {
        $this->heurePriseAt = $heurePriseAt;

        return $this;
    }
}
