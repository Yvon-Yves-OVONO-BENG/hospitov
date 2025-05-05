<?php

namespace App\Entity;

use App\Repository\LigneConsultationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneConsultationRepository::class)]
class LigneConsultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneConsultations')]
    private ?Consultation $consultation = null;

    #[ORM\ManyToOne(inversedBy: 'ligneConsultations')]
    private ?Produit $examen = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): static
    {
        $this->consultation = $consultation;

        return $this;
    }

    public function getExamen(): ?Produit
    {
        return $this->examen;
    }

    public function setExamen(?Produit $examen): static
    {
        $this->examen = $examen;

        return $this;
    }
}
