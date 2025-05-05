<?php

namespace App\Entity;

use App\Repository\CategoriePermisDeConduireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriePermisDeConduireRepository::class)]
class CategoriePermisDeConduire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categoriePermisDeConduire = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'categoriePermisDeconduire')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoriePermisDeConduire(): ?string
    {
        return $this->categoriePermisDeConduire;
    }

    public function setCategoriePermisDeConduire(?string $categoriePermisDeConduire): static
    {
        $this->categoriePermisDeConduire = $categoriePermisDeConduire;

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
            $user->setCategoriePermisDeconduire($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCategoriePermisDeconduire() === $this) {
                $user->setCategoriePermisDeconduire(null);
            }
        }

        return $this;
    }
  
}
