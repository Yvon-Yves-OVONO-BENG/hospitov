<?php

namespace App\Entity;

use App\Repository\FichierResultatExamenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: FichierResultatExamenRepository::class)]
class FichierResultatExamen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping:"fichiers_examens", fileNameProperty:"fichierResultat")]
    /**
    *
    * @var File|null
     */
    private $imageFile;

    #[ORM\Column(length: 255)]
    private ?string $fichierResultat = null;

    #[ORM\ManyToOne(inversedBy: 'fichierResultatExamens')]
    private ?ResultatExamen $resultatExamen = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $nomFichier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the image file
     *
     * @param File|null $imageFile
     * @return void
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if($imageFile !== null)
        {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    
    public function getFichierResultat(): ?string
    {
        return $this->fichierResultat;
    }

    public function setFichierResultat(string $fichierResultat): static
    {
        $this->fichierResultat = $fichierResultat;

        return $this;
    }

    public function getResultatExamen(): ?ResultatExamen
    {
        return $this->resultatExamen;
    }

    public function setResultatExamen(?ResultatExamen $resultatExamen): static
    {
        $this->resultatExamen = $resultatExamen;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }
}
