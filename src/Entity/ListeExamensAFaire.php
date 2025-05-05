<?php

namespace App\Entity;

use App\Repository\ListeExamensAFaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeExamensAFaireRepository::class)]
class ListeExamensAFaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'listeExamensAFaires')]
    private ?Produit $examen = null;

    #[ORM\ManyToOne(inversedBy: 'listeExamensAFaires')]
    private ?ResultatExamen $resultatExamen = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getResultatExamen(): ?ResultatExamen
    {
        return $this->resultatExamen;
    }

    public function setResultatExamen(?ResultatExamen $resultatExamen): static
    {
        $this->resultatExamen = $resultatExamen;

        return $this;
    }
}
