<?php

namespace App\Entity;

use App\Repository\EtatUtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatUtilisateurRepository::class)]
class EtatUtilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatUtilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtatUtilisateur(): ?string
    {
        return $this->etatUtilisateur;
    }

    public function setEtatUtilisateur(?string $etatUtilisateur): static
    {
        $this->etatUtilisateur = $etatUtilisateur;

        return $this;
    }

}
