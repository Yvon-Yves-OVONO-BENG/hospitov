<?php

namespace App\Entity;

use App\Repository\BulletinSalaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BulletinSalaireRepository::class)]
#[ORM\Table(name: "BulletinSalaire", uniqueConstraints: [ 
    new ORM\UniqueConstraint(name: "unique_bulletin", columns: ["personnel", "mois", "annee"])])]
class BulletinSalaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bulletinSalaires')]
    private ?User $personnel = null;

    #[ORM\Column]
    private ?int $mois = null;

    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\Column]
    private ?float $salaireBrute = null;

    #[ORM\Column]
    private ?float $totalGains = null;

    #[ORM\Column]
    private ?float $totalRetenues = null;

    #[ORM\Column]
    private ?float $montantIrpp = null;

    #[ORM\Column]
    private ?float $netAPayer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePaiementAt = null;

    #[ORM\ManyToOne(inversedBy: 'caissiereBulletinDePaie')]
    private ?User $caissiere = null;

    #[ORM\OneToMany(targetEntity: BulletinGain::class, mappedBy: 'bulletinSalaire')]
    private Collection $gains;

    #[ORM\OneToMany(targetEntity: BulletinRetenue::class, mappedBy: 'bulletinSalaire')]
    private Collection $retenues;

    #[ORM\Column]
    private ?float $totalPrimeSpeciale = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column]
    private ?bool $paye = null;

    public function __construct()
    {
        $this->gains = new ArrayCollection();
        $this->retenues = new ArrayCollection();
    }

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

    public function getSalaireBrute(): ?float
    {
        return $this->salaireBrute;
    }

    public function setSalaireBrute(float $salaireBrute): static
    {
        $this->salaireBrute = $salaireBrute;

        return $this;
    }

    public function getTotalGains(): ?float
    {
        return $this->totalGains;
    }

    public function setTotalGains(float $totalGains): static
    {
        $this->totalGains = $totalGains;

        return $this;
    }

    public function getTotalRetenues(): ?float
    {
        return $this->totalRetenues;
    }

    public function setTotalRetenues(float $totalRetenues): static
    {
        $this->totalRetenues = $totalRetenues;

        return $this;
    }

    public function getMontantIrpp(): ?float
    {
        return $this->montantIrpp;
    }

    public function setMontantIrpp(float $montantIrpp): static
    {
        $this->montantIrpp = $montantIrpp;

        return $this;
    }

    public function getNetAPayer(): ?float
    {
        return $this->netAPayer;
    }

    public function setNetAPayer(float $netAPayer): static
    {
        $this->netAPayer = $netAPayer;

        return $this;
    }

    public function getDatePaiementAt(): ?\DateTimeInterface
    {
        return $this->datePaiementAt;
    }

    public function setDatePaiementAt(\DateTimeInterface $datePaiementAt): static
    {
        $this->datePaiementAt = $datePaiementAt;

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

    /**
     * @return Collection<int, BulletinGain>
     */
    public function getGains(): Collection
    {
        return $this->gains;
    }

    public function addGain(BulletinGain $gain): static
    {
        if (!$this->gains->contains($gain)) {
            $this->gains->add($gain);
            $gain->setBulletinSalaire($this);
        }

        return $this;
    }

    public function removeGain(BulletinGain $gain): static
    {
        if ($this->gains->removeElement($gain)) {
            // set the owning side to null (unless already changed)
            if ($gain->getBulletinSalaire() === $this) {
                $gain->setBulletinSalaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BulletinRetenue>
     */
    public function getRetenues(): Collection
    {
        return $this->retenues;
    }

    public function addRetenue(BulletinRetenue $retenue): static
    {
        if (!$this->retenues->contains($retenue)) {
            $this->retenues->add($retenue);
            $retenue->setBulletinSalaire($this);
        }

        return $this;
    }

    public function removeRetenue(BulletinRetenue $retenue): static
    {
        if ($this->retenues->removeElement($retenue)) {
            // set the owning side to null (unless already changed)
            if ($retenue->getBulletinSalaire() === $this) {
                $retenue->setBulletinSalaire(null);
            }
        }

        return $this;
    }

    public function getTotalPrimeSpeciale(): ?float
    {
        return $this->totalPrimeSpeciale;
    }

    public function setTotalPrimeSpeciale(float $totalPrimeSpeciale): static
    {
        $this->totalPrimeSpeciale = $totalPrimeSpeciale;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

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

}
