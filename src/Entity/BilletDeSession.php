<?php

namespace App\Entity;

use App\Repository\BilletDeSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BilletDeSessionRepository::class)]
class BilletDeSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'billetDeSessions')]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'billetDeSessions')]
    private ?Produit $produit = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateBilletDeSessionAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $etat = null;

    #[ORM\ManyToOne(inversedBy: 'billetDeSessions')]
    private ?User $caissiere = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heureAt = null;

    #[ORM\OneToMany(targetEntity: ParametresVitaux::class, mappedBy: 'billetDeSession')]
    private Collection $parametresVitauxes;

    #[ORM\OneToMany(targetEntity: ResultatExamen::class, mappedBy: 'billetDeSession')]
    private Collection $resultatExamens;

    public function __construct()
    {
        $this->parametresVitauxes = new ArrayCollection();
        $this->resultatExamens = new ArrayCollection();
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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getDateBilletDeSessionAt(): ?\DateTimeInterface
    {
        return $this->dateBilletDeSessionAt;
    }

    public function setDateBilletDeSessionAt(\DateTimeInterface $dateBilletDeSessionAt): static
    {
        $this->dateBilletDeSessionAt = $dateBilletDeSessionAt;

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

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCaissiere(): ?User
    {
        return $this->caissiere;
    }

    public function setCaissiere(?User $caissiere): static
    {
        $this->caissiere = $caissiere;

        return $this;
    }

    public function getHeureAt(): ?\DateTimeInterface
    {
        return $this->heureAt;
    }

    public function setHeureAt(\DateTimeInterface $heureAt): static
    {
        $this->heureAt = $heureAt;

        return $this;
    }

    /**
     * @return Collection<int, ParametresVitaux>
     */
    public function getParametresVitauxes(): Collection
    {
        return $this->parametresVitauxes;
    }

    public function addParametresVitaux(ParametresVitaux $parametresVitaux): static
    {
        if (!$this->parametresVitauxes->contains($parametresVitaux)) {
            $this->parametresVitauxes->add($parametresVitaux);
            $parametresVitaux->setBilletDeSession($this);
        }

        return $this;
    }

    public function removeParametresVitaux(ParametresVitaux $parametresVitaux): static
    {
        if ($this->parametresVitauxes->removeElement($parametresVitaux)) {
            // set the owning side to null (unless already changed)
            if ($parametresVitaux->getBilletDeSession() === $this) {
                $parametresVitaux->setBilletDeSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ResultatExamen>
     */
    public function getResultatExamens(): Collection
    {
        return $this->resultatExamens;
    }

    public function addResultatExamen(ResultatExamen $resultatExamen): static
    {
        if (!$this->resultatExamens->contains($resultatExamen)) {
            $this->resultatExamens->add($resultatExamen);
            $resultatExamen->setBilletDeSession($this);
        }

        return $this;
    }

    public function removeResultatExamen(ResultatExamen $resultatExamen): static
    {
        if ($this->resultatExamens->removeElement($resultatExamen)) {
            // set the owning side to null (unless already changed)
            if ($resultatExamen->getBilletDeSession() === $this) {
                $resultatExamen->setBilletDeSession(null);
            }
        }

        return $this;
    }
}
