<?php

namespace App\Entity;

use App\Repository\AmbulanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: AmbulanceRepository::class)]
#[ORM\Table(name: '`Ambulance`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_IMMATRICULATION', fields: ['immatriculation'])]
#[UniqueEntity(fields: ['immatriculation'], message: 'Une ambulance existe déjà avec cette immatriculation !')]
class Ambulance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $immatriculation = null;

    #[ORM\ManyToOne(inversedBy: 'ambulances')]
    private ?Modele $modele = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ambulance = null;

    #[ORM\ManyToOne(inversedBy: 'ambulances')]
    private ?EtatAmbulance $etatAmbulance = null;

    #[Vich\UploadableField(mapping:"ambulances_image", fileNameProperty:"photo")]
    /**
    *
    * @var File|null
     */
    private $photoFile;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $anneeAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    #[ORM\OneToMany(targetEntity: AttributionAmbulance::class, mappedBy: 'ambulance')]
    private Collection $attributionAmbulances;

    #[ORM\Column(nullable: true)]
    private ?bool $libre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $supprime = null;

    #[ORM\Column(nullable: true)]
    private ?bool $service = null;

    public function __construct()
    {
        $this->attributionAmbulances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(?string $immatriculation): static
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getAmbulance(): ?string
    {
        return $this->ambulance;
    }

    public function setAmbulance(?string $ambulance): static
    {
        $this->ambulance = $ambulance;

        return $this;
    }

    public function getEtatAmbulance(): ?EtatAmbulance
    {
        return $this->etatAmbulance;
    }

    public function setEtatAmbulance(?EtatAmbulance $etatAmbulance): static
    {
        $this->etatAmbulance = $etatAmbulance;

        return $this;
    }

    /**
     * Set the photo file
     *
     * @param File|null $photoFile
     * @return void
     */
    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;

        if($photoFile !== null)
        {
            $this->setUpdateAt(new \DateTime());
        }
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }
    
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getAnneeAt(): ?\DateTimeInterface
    {
        return $this->anneeAt;
    }

    public function setAnneeAt(\DateTimeInterface $anneeAt): static
    {
        $this->anneeAt = $anneeAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection<int, AttributionAmbulance>
     */
    public function getAttributionAmbulances(): Collection
    {
        return $this->attributionAmbulances;
    }

    public function addAttributionAmbulance(AttributionAmbulance $attributionAmbulance): static
    {
        if (!$this->attributionAmbulances->contains($attributionAmbulance)) {
            $this->attributionAmbulances->add($attributionAmbulance);
            $attributionAmbulance->setAmbulance($this);
        }

        return $this;
    }

    public function removeAttributionAmbulance(AttributionAmbulance $attributionAmbulance): static
    {
        if ($this->attributionAmbulances->removeElement($attributionAmbulance)) {
            // set the owning side to null (unless already changed)
            if ($attributionAmbulance->getAmbulance() === $this) {
                $attributionAmbulance->setAmbulance(null);
            }
        }

        return $this;
    }

    public function isLibre(): ?bool
    {
        return $this->libre;
    }

    public function setLibre(?bool $libre): static
    {
        $this->libre = $libre;

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

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(?bool $supprime): static
    {
        $this->supprime = $supprime;

        return $this;
    }

    public function isService(): ?bool
    {
        return $this->service;
    }

    public function setService(?bool $service): static
    {
        $this->service = $service;

        return $this;
    }
}
