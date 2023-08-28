<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'clients')]
#[UniqueEntity(fields: ['courriel'], message: 'Ce courriel est déjà utilisé.')]
class Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idClient')]
    private ?int $idClient = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: "Votre adresse courriel: {{ value }} est invalide")]
    private ?string $courriel = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(min: 2, minMessage: "Le nom doit contenir {{ limit }} caractères minimum")]
    #[Assert\Length(max: 30, maxMessage: "Le nom doit contenir {{ limit }} caractères maximum")]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(min: 2, minMessage: "Le prénom doit contenir {{ limit }} caractères minimum")]
    #[Assert\Length(max: 30, maxMessage: "Le prénom doit contenir {{ limit }} caractères maximum")]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(min: 5, minMessage: "L'adresse doit contenir {{ limit }} caractères minimum")]
    #[Assert\Length(max: 100, maxMessage: "L'adresse doit contenir {{ limit }} caractères maximum")]
    private ?string $adresse = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(min: 3, minMessage: "La ville doit contenir {{ limit }} caractères minimum")]
    #[Assert\Length(max: 30, maxMessage: "La ville doit contenir {{ limit }} caractères maximum")]
    private ?string $ville = null;

    #[ORM\Column(name: 'codePostal', length: 6)]
    #[Assert\Regex(pattern: "/^(?![DFIOQU])[A-VXY][0-9][A-Z][0-9][A-Z][0-9]$/", message: "Code postal invalide (Ne peux pas commencer par : DFIOQUZ)")]
    private ?string $codePostal = null;

    #[ORM\Column(length: 2)]
    #[Assert\Length(max: 2, maxMessage: "La province doit contenir {{ limit }} caractères maximum")]
    private ?string $province = null;

    #[ORM\Column(length: 10)]
    #[Assert\Regex(pattern: "/^[0-9]{10}$/", message: "Votre téléphone doit contenir 10 chiffres")]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Commande::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $listeCommande;

    public function __construct()
    {
        $this->listeCommande = new ArrayCollection();
    }

    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function getCourriel(): ?string
    {
        return $this->courriel;
    }

    public function setCourriel(string $courriel): self
    {
        $this->courriel = $courriel;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->courriel;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getListeCommande(): Collection
    {
        return $this->listeCommande;
    }

    public function addListeCommande(Commande $listeCommande): self
    {
        if (!$this->listeCommande->contains($listeCommande)) {
            $this->listeCommande->add($listeCommande);
            $listeCommande->setClient($this);
        }

        return $this;
    }

    public function removeListeCommande(Commande $listeCommande): self
    {
        if ($this->listeCommande->removeElement($listeCommande)) {
            // set the owning side to null (unless already changed)
            if ($listeCommande->getClient() === $this) {
                $listeCommande->setClient(null);
            }
        }

        return $this;
    }
}
