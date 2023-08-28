<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatRepository::class)]
#[ORM\Table(name: 'achats')]
class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idAchat')]
    private ?int $idAchat = null;

    #[ORM\Column(length: 100)]
    private int $quantite;

    #[ORM\Column(name: 'prixAchat')]
    private float $prixAchat;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idProduit', referencedColumnName: 'idProduit', nullable: false)]
    private Produit $produit;

    #[ORM\ManyToOne(inversedBy: 'listeAchat')]
    #[ORM\JoinColumn(name: 'idCommande', referencedColumnName: 'idCommande', nullable: false)]
    private ?Commande $commande = null;

    public function __construct($quantite, $produit)
    {
        $this->quantite = $quantite;
        $this->prixAchat = $produit->getPrix();
        $this->produit = $produit;
    }

    public function getIdAchat()
    {
        return $this->idAchat;
    }

    public function update($quantite)
    {
        $this->quantite = $quantite;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    public function getProduit()
    {
        return $this->produit;
    }

    public function setProduit($produit)
    {
        $this->produit = $produit;
        return $this;
    }

    public function calculerTotal()
    {
        $prixTotal = 0;
        if ($this->getQuantite() > 0) {
            $prixTotal = $this->getPrixAchat() * $this->getQuantite();
        }
        return $prixTotal;
    }
   

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
