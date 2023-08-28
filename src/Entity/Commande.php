<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commandes')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idCommande')]
    private ?int $idCommande = null;

    #[ORM\Column(name: 'dateCommande', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(name: 'dateLivraison', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateLivraison = null;

    #[ORM\Column(name: 'tauxTPS')]
    private ?float $tauxTPS = null;

    #[ORM\Column(name: 'tauxTVQ')]
    private ?float $tauxTVQ = null;

    #[ORM\Column(name: 'fraisLivraison')]
    private ?float $fraisLivraison = null;

    #[ORM\Column(length: 50)]
    private ?string $etat = null;

    #[ORM\Column(name: 'stripeIntent', length: 255)]
    private ?string $stripeIntent = null;

    #[ORM\ManyToOne(inversedBy: 'listeCommande')]
    #[ORM\JoinColumn(name: 'idClient', referencedColumnName: 'idClient', nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Achat::class, cascade: ['persist'])]
    private Collection $listeAchat;

    public function __construct($user, $panier, $paymentIntent)
    {
        $this->dateCommande = new DateTime();
        $this->dateLivraison = null;
        $this->tauxTPS = Constantes::TPS;
        $this->tauxTVQ = Constantes::TVQ;
        $this->fraisLivraison = Constantes::FRAIS_LIVRAISON;
        $this->etat = 'En préparation';
        $this->stripeIntent = $paymentIntent;
        $this->client = $user;

        $this->listeAchat = new ArrayCollection();

        foreach ($panier->getAchats() as $achat) {
            $this->listeAchat->add($achat);
            $achat->setCommande($this);
        }
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function getDateCommandeFormat()
    {
        return $this->dateCommande->format('Y-m-d H:i:s');
    }

    public function getQuantiteTotal()
    {
        $totalArticle = 0;
        foreach ($this->listeAchat as $achat) {
            $totalArticle += $achat->getQuantite();
        }

        return $totalArticle;
    }

    public function caculerGrandTotal()
    {
        $prix = 0;

        foreach ($this->listeAchat as $achat) {
            $prix += $achat->calculerTotal();
        }
        return ($prix * $this->getTauxTPS()) + ($prix * $this->getTauxTVQ()) + $prix + $this->getFraisLivraison();
    }

    public function calculerSousTotal()
    {
        $prixTotal = 0;
        foreach ($this->listeAchat as $achat) {
            if ($achat->getQuantite() > 0) {
                $prixTotal += $achat->getQuantite() * $achat->getPrixAchat();
            }
        }

        return $prixTotal;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getTauxTPS(): ?float
    {
        return $this->tauxTPS;
    }

    public function setTauxTPS(float $tauxTPS): self
    {
        $this->tauxTPS = $tauxTPS;

        return $this;
    }

    public function getTauxTVQ(): ?float
    {
        return $this->tauxTVQ;
    }

    public function setTauxTVQ(float $tauxTVQ): self
    {
        $this->tauxTVQ = $tauxTVQ;

        return $this;
    }

    public function getFraisLivraison(): ?float
    {
        return $this->fraisLivraison;
    }

    public function setFraisLivraison(float $fraisLivraison): self
    {
        $this->fraisLivraison = $fraisLivraison;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getStripeIntent(): ?string
    {
        return $this->stripeIntent;
    }

    public function setStripeIntent(string $stripeIntent): self
    {
        $this->stripeIntent = $stripeIntent;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Achat>
     */
    public function getListeAchat(): Collection
    {
        return $this->listeAchat;
    }

    public function addListeAchat(Achat $listeAchat): self
    {
        if (!$this->listeAchat->contains($listeAchat)) {
            $this->listeAchat->add($listeAchat);
            $listeAchat->setCommande($this);
        }

        return $this;
    }

    public function removeListeAchat(Achat $listeAchat): self
    {
        if ($this->listeAchat->removeElement($listeAchat)) {
            // set the owning side to null (unless already changed)
            if ($listeAchat->getCommande() === $this) {
                $listeAchat->setCommande(null);
            }
        }

        return $this;
    }
}
