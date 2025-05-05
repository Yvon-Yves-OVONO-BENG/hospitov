<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'Un compte existant utilise déjà ce nom de connexion')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[Assert\IdenticalTo(propertyPath:'password', message:'Les mots de passe doivent être identiques')]
    private $confirmPassword;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'caissiere')]
    private Collection $factures;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(nullable: true)]
    private ?bool $etat = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?TypeUtilisateur $typeUtilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'enregistrePar')]
    private Collection $produits;

    #[ORM\OneToMany(targetEntity: Lot::class, mappedBy: 'enregistrePar')]
    private Collection $lots;

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'secretaire')]
    private Collection $commandes;

    #[ORM\OneToMany(targetEntity: ReponseQuestion::class, mappedBy: 'user')]
    private Collection $reponseQuestions;

    #[ORM\OneToMany(targetEntity: HistoriquePaiement::class, mappedBy: 'recuPar')]
    private Collection $historiquePaiements;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?CategoriePermisDeConduire $categoriePermisDeconduire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroPermisDeConduire = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Specialite $specialite = null;

    #[ORM\Column]
    private ?bool $supprime = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaireBrute = null;

    #[ORM\OneToMany(targetEntity: BulletinSalaire::class, mappedBy: 'personnel')]
    private Collection $bulletinSalaires;

    #[ORM\OneToMany(targetEntity: BulletinSalaire::class, mappedBy: 'caissiere')]
    private Collection $caissiereBulletinDePaie;

    #[ORM\OneToMany(targetEntity: PrimeSpeciale::class, mappedBy: 'personnel')]
    private Collection $primeSpeciales;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?StatutPersonnel $statutPersonnel = null;

    #[ORM\OneToMany(targetEntity: ParametresVitaux::class, mappedBy: 'infirmier')]
    private Collection $parametresVitauxes;

    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'medecin')]
    private Collection $consultations;

    #[ORM\OneToMany(targetEntity: ResultatExamen::class, mappedBy: 'laborantin')]
    private Collection $resultatExamens;

    #[ORM\OneToMany(targetEntity: BilletDeSession::class, mappedBy: 'caissiere')]
    private Collection $billetDeSessions;

    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'prescripteur')]
    private Collection $prescripteurs;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->lots = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->reponseQuestions = new ArrayCollection();
        $this->historiquePaiements = new ArrayCollection();
        $this->bulletinSalaires = new ArrayCollection();
        $this->caissiereBulletinDePaie = new ArrayCollection();
        $this->primeSpeciales = new ArrayCollection();
        $this->parametresVitauxes = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->resultatExamens = new ArrayCollection();
        $this->billetDeSessions = new ArrayCollection();
        $this->prescripteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getConfirmPassword(): string
    {
        return (string) $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

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
            $facture->setCaissiere($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getCaissiere() === $this) {
                $facture->setCaissiere(null);
            }
        }

        return $this;
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

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTypeUtilisateur(): ?TypeUtilisateur
    {
        return $this->typeUtilisateur;
    }

    public function setTypeUtilisateur(?TypeUtilisateur $typeUtilisateur): static
    {
        $this->typeUtilisateur = $typeUtilisateur;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function serialize()
    {
        $this->photo = base64_encode($this->photo);
    }

    public function unserialize($serialized)
    {
        $this->photo = base64_decode($this->photo);

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
            $produit->setEnregistrePar($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getEnregistrePar() === $this) {
                $produit->setEnregistrePar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lot>
     */
    public function getLots(): Collection
    {
        return $this->lots;
    }

    public function addLot(Lot $lot): static
    {
        if (!$this->lots->contains($lot)) {
            $this->lots->add($lot);
            $lot->setEnregistrePar($this);
        }

        return $this;
    }

    public function removeLot(Lot $lot): static
    {
        if ($this->lots->removeElement($lot)) {
            // set the owning side to null (unless already changed)
            if ($lot->getEnregistrePar() === $this) {
                $lot->setEnregistrePar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setSecretaire($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getSecretaire() === $this) {
                $commande->setSecretaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReponseQuestion>
     */
    public function getReponseQuestions(): Collection
    {
        return $this->reponseQuestions;
    }

    public function addReponseQuestion(ReponseQuestion $reponseQuestion): static
    {
        if (!$this->reponseQuestions->contains($reponseQuestion)) {
            $this->reponseQuestions->add($reponseQuestion);
            $reponseQuestion->setUser($this);
        }

        return $this;
    }

    public function removeReponseQuestion(ReponseQuestion $reponseQuestion): static
    {
        if ($this->reponseQuestions->removeElement($reponseQuestion)) {
            // set the owning side to null (unless already changed)
            if ($reponseQuestion->getUser() === $this) {
                $reponseQuestion->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HistoriquePaiement>
     */
    public function getHistoriquePaiements(): Collection
    {
        return $this->historiquePaiements;
    }

    public function addHistoriquePaiement(HistoriquePaiement $historiquePaiement): static
    {
        if (!$this->historiquePaiements->contains($historiquePaiement)) {
            $this->historiquePaiements->add($historiquePaiement);
            $historiquePaiement->setRecuPar($this);
        }

        return $this;
    }

    public function removeHistoriquePaiement(HistoriquePaiement $historiquePaiement): static
    {
        if ($this->historiquePaiements->removeElement($historiquePaiement)) {
            // set the owning side to null (unless already changed)
            if ($historiquePaiement->getRecuPar() === $this) {
                $historiquePaiement->setRecuPar(null);
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

    public function getCategoriePermisDeconduire(): ?CategoriePermisDeConduire
    {
        return $this->categoriePermisDeconduire;
    }

    public function setCategoriePermisDeconduire(?CategoriePermisDeConduire $categoriePermisDeconduire): static
    {
        $this->categoriePermisDeconduire = $categoriePermisDeconduire;

        return $this;
    }

    public function getNumeroPermisDeConduire(): ?string
    {
        return $this->numeroPermisDeConduire;
    }

    public function setNumeroPermisDeConduire(string $numeroPermisDeConduire): static
    {
        $this->numeroPermisDeConduire = $numeroPermisDeConduire;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(bool $supprime): static
    {
        $this->supprime = $supprime;

        return $this;
    }

    public function getSalaireBrute(): ?float
    {
        return $this->salaireBrute;
    }

    public function setSalaireBrute(?float $salaireBrute): static
    {
        $this->salaireBrute = $salaireBrute;

        return $this;
    }

    /**
     * @return Collection<int, BulletinSalaire>
     */
    public function getBulletinSalaires(): Collection
    {
        return $this->bulletinSalaires;
    }

    public function addBulletinSalaire(BulletinSalaire $bulletinSalaire): static
    {
        if (!$this->bulletinSalaires->contains($bulletinSalaire)) {
            $this->bulletinSalaires->add($bulletinSalaire);
            $bulletinSalaire->setPersonnel($this);
        }

        return $this;
    }

    public function removeBulletinSalaire(BulletinSalaire $bulletinSalaire): static
    {
        if ($this->bulletinSalaires->removeElement($bulletinSalaire)) {
            // set the owning side to null (unless already changed)
            if ($bulletinSalaire->getPersonnel() === $this) {
                $bulletinSalaire->setPersonnel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BulletinSalaire>
     */
    public function getCaissiereBulletinDePaie(): Collection
    {
        return $this->caissiereBulletinDePaie;
    }

    public function addCaissiereBulletinDePaie(BulletinSalaire $caissiereBulletinDePaie): static
    {
        if (!$this->caissiereBulletinDePaie->contains($caissiereBulletinDePaie)) {
            $this->caissiereBulletinDePaie->add($caissiereBulletinDePaie);
            $caissiereBulletinDePaie->setCaissiere($this);
        }

        return $this;
    }

    public function removeCaissiereBulletinDePaie(BulletinSalaire $caissiereBulletinDePaie): static
    {
        if ($this->caissiereBulletinDePaie->removeElement($caissiereBulletinDePaie)) {
            // set the owning side to null (unless already changed)
            if ($caissiereBulletinDePaie->getCaissiere() === $this) {
                $caissiereBulletinDePaie->setCaissiere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrimeSpeciale>
     */
    public function getPrimeSpeciales(): Collection
    {
        return $this->primeSpeciales;
    }

    public function addPrimeSpeciale(PrimeSpeciale $primeSpeciale): static
    {
        if (!$this->primeSpeciales->contains($primeSpeciale)) {
            $this->primeSpeciales->add($primeSpeciale);
            $primeSpeciale->setPersonnel($this);
        }

        return $this;
    }

    public function removePrimeSpeciale(PrimeSpeciale $primeSpeciale): static
    {
        if ($this->primeSpeciales->removeElement($primeSpeciale)) {
            // set the owning side to null (unless already changed)
            if ($primeSpeciale->getPersonnel() === $this) {
                $primeSpeciale->setPersonnel(null);
            }
        }

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getStatutPersonnel(): ?StatutPersonnel
    {
        return $this->statutPersonnel;
    }

    public function setStatutPersonnel(?StatutPersonnel $statutPersonnel): static
    {
        $this->statutPersonnel = $statutPersonnel;

        return $this;
    }

    /**
     * @return Collection<int, ParametresVitaux>
     */
    public function getParametresVitauxes(): Collection
    {
        return $this->parametresVitauxes;
    }

    public function addParametresVitaux(ParametresVitaux $parametresVitaux): static
    {
        if (!$this->parametresVitauxes->contains($parametresVitaux)) {
            $this->parametresVitauxes->add($parametresVitaux);
            $parametresVitaux->setInfirmier($this);
        }

        return $this;
    }

    public function removeParametresVitaux(ParametresVitaux $parametresVitaux): static
    {
        if ($this->parametresVitauxes->removeElement($parametresVitaux)) {
            // set the owning side to null (unless already changed)
            if ($parametresVitaux->getInfirmier() === $this) {
                $parametresVitaux->setInfirmier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setMedecin($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getMedecin() === $this) {
                $consultation->setMedecin(null);
            }
        }

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
            $resultatExamen->setLaborantin($this);
        }

        return $this;
    }

    public function removeResultatExamen(ResultatExamen $resultatExamen): static
    {
        if ($this->resultatExamens->removeElement($resultatExamen)) {
            // set the owning side to null (unless already changed)
            if ($resultatExamen->getLaborantin() === $this) {
                $resultatExamen->setLaborantin(null);
            }
        }

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
            $billetDeSession->setCaissiere($this);
        }

        return $this;
    }

    public function removeBilletDeSession(BilletDeSession $billetDeSession): static
    {
        if ($this->billetDeSessions->removeElement($billetDeSession)) {
            // set the owning side to null (unless already changed)
            if ($billetDeSession->getCaissiere() === $this) {
                $billetDeSession->setCaissiere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getPrescripteurs(): Collection
    {
        return $this->prescripteurs;
    }

    public function addPrescripteur(Facture $prescripteur): static
    {
        if (!$this->prescripteurs->contains($prescripteur)) {
            $this->prescripteurs->add($prescripteur);
            $prescripteur->setPrescripteur($this);
        }

        return $this;
    }

    public function removePrescripteur(Facture $prescripteur): static
    {
        if ($this->prescripteurs->removeElement($prescripteur)) {
            // set the owning side to null (unless already changed)
            if ($prescripteur->getPrescripteur() === $this) {
                $prescripteur->setPrescripteur(null);
            }
        }

        return $this;
    }
}
