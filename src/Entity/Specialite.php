<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $supprime = null;

    #[ORM\OneToMany(targetEntity: Chambre::class, mappedBy: 'specialite')]
    private Collection $chambres;

    #[ORM\ManyToOne(inversedBy: 'specialites')]
    private ?User $chefDeDepartement = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'specialite')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'specialite')]
    private Collection $produits;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

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
            $chambre->setSpecialite($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): static
    {
        if ($this->chambres->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getSpecialite() === $this) {
                $chambre->setSpecialite(null);
            }
        }

        return $this;
    }

    public function getChefDeDepartement(): ?User
    {
        return $this->chefDeDepartement;
    }

    public function setChefDeDepartement(?User $chefDeDepartement): static
    {
        $this->chefDeDepartement = $chefDeDepartement;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSpecialite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSpecialite() === $this) {
                $user->setSpecialite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setSpecialite($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getSpecialite() === $this) {
                $produit->setSpecialite(null);
            }
        }

        return $this;
    }
}
