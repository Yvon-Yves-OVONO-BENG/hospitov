<?php

namespace App\Entity;

use App\Repository\BatimentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: BatimentRepository::class)]
class Batiment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $batiment = null;

    #[ORM\OneToMany(targetEntity: Chambre::class, mappedBy: 'batiment')]
    private Collection $chambres;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $supprime = null;

    #[Vich\UploadableField(mapping:"batiments_image", fileNameProperty:"photo")]
    /**
    *
    * @var File|null
     */
    private $photoFile;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $enService = null;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(string $batiment): static
    {
        $this->batiment = $batiment;

        return $this;
    }

    /**
     * @return Collection<int, Chambre>
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): static
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres->add($chambre);
            $chambre->setBatiment($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): static
    {
        if ($this->chambres->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getBatiment() === $this) {
                $chambre->setBatiment(null);
            }
        }

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

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function isEnService(): ?bool
    {
        return $this->enService;
    }

    public function setEnService(?bool $enService): static
    {
        $this->enService = $enService;

        return $this;
    }
}
