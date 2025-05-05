<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'patient')]
    private Collection $factures;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaissanceAt = null;

    #[ORM\ManyToOne(inversedBy: 'patients')]
    private ?Pays $pays = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $villeResidence = null;

    #[ORM\Column(nullable: true)]
    private ?bool $termine = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numCni = null;

    #[ORM\ManyToOne(inversedBy: 'patients')]
    private ?GroupeSanguin $groupeSanguin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: BilletDeSession::class, mappedBy: 'patient')]
    private Collection $billetDeSessions;

    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'patient')]
    private Collection $rendezVouses;

    #[ORM\ManyToOne(inversedBy: 'patients')]
    private ?Genre $genre = null;

    #[ORM\OneToMany(targetEntity: ResultatExamen::class, mappedBy: 'patient')]
    private Collection $resultatExamens;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
        $this->billetDeSessions = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
        $this->resultatExamens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setPatient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getPatient() === $this) {
                $facture->setPatient(null);
            }
        }

        return $this;
    }

    public function getDateNaissanceAt(): ?\DateTimeInterface
    {
        return $this->dateNaissanceAt;
    }

    public function setDateNaissanceAt(?\DateTimeInterface $dateNaissanceAt): static
    {
        $this->dateNaissanceAt = $dateNaissanceAt;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getVilleResidence(): ?string
    {
        return $this->villeResidence;
    }

    public function setVilleResidence(?string $villeResidence): static
    {
        $this->villeResidence = $villeResidence;

        return $this;
    }

    public function isTermine(): ?bool
    {
        return $this->termine;
    }

    public function setTermine(?bool $termine): static
    {
        $this->termine = $termine;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getNumCni(): ?string
    {
        return $this->numCni;
    }

    public function setNumCni(string $numCni): static
    {
        $this->numCni = $numCni;

        return $this;
    }

    public function getGroupeSanguin(): ?GroupeSanguin
    {
        return $this->groupeSanguin;
    }

    public function setGroupeSanguin(?GroupeSanguin $groupeSanguin): static
    {
        $this->groupeSanguin = $groupeSanguin;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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
     * @return Collection<int, BilletDeSession>
     */
    public function getBilletDeSessions(): Collection
    {
        return $this->billetDeSessions;
    }

    public function addBilletDeSession(BilletDeSession $billetDeSession): static
    {
        if (!$this->billetDeSessions->contains($billetDeSession)) {
            $this->billetDeSessions->add($billetDeSession);
            $billetDeSession->setPatient($this);
        }

        return $this;
    }

    public function removeBilletDeSession(BilletDeSession $billetDeSession): static
    {
        if ($this->billetDeSessions->removeElement($billetDeSession)) {
            // set the owning side to null (unless already changed)
            if ($billetDeSession->getPatient() === $this) {
                $billetDeSession->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): static
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setPatient($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getPatient() === $this) {
                $rendezVouse->setPatient(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

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
            $resultatExamen->setPatient($this);
        }

        return $this;
    }

    public function removeResultatExamen(ResultatExamen $resultatExamen): static
    {
        if ($this->resultatExamens->removeElement($resultatExamen)) {
            // set the owning side to null (unless already changed)
            if ($resultatExamen->getPatient() === $this) {
                $resultatExamen->setPatient(null);
            }
        }

        return $this;
    }

}
