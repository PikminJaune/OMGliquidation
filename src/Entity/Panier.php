<?php

namespace App\Entity;

use App\Entity\Constantes;
use App\Entity\Achat;

class Panier
{
    private $achats = [];

    public function ajouterAchat($produit, $quantite)
    {
        if ($this->estDansLePanier($produit->getIdProduit())) {
            //Boucle pour retrouver l'item qui exite dans le panier et ajoute 1 à sa quantité
            foreach ($this->achats as $unAchat) {
                if ($unAchat->getProduit()->getIdProduit() == $produit->getIdProduit()) {
                    $unAchat->setQuantite($unAchat->getQuantite() + 1);
                }
            }
        } else {
            $achat = new Achat($quantite, $produit);
            $this->achats[] = $achat;
        }
    }

    public function supprimerAchat($idProduit)
    {
        foreach ($this->achats as $key => $achat) {
            if ($achat->getProduit()->getIdProduit() == $idProduit) {
                unset($this->achats[$key]);
            }
        }
    }

    public function update($newAchat)
    {
        if (count($this->achats) > 0) {
            $achatQuantite = $newAchat["inputQuantite"];

            foreach ($this->achats as $key => $achat) {
                $achat->update($achatQuantite[$key]);
            }
        }
    }

    public function calculerSousTotal()
    {
        $prixTotal = 0;
        foreach ($this->achats as $achat) {
            if ($achat->getQuantite() > 0) {
                $prixTotal += $achat->getQuantite() * $achat->getPrixAchat();
            }
        }

        return $prixTotal;
    }

    public function caculerGrandTotal()
    {
        $prix = 0;

        foreach ($this->achats as $achat) {
            $prix += $achat->calculerTotal();
        }
        return ($prix * $this->getTPS()) + ($prix * $this->getTVQ()) + $prix + $this->getFraisLivraison();
    }


    public function getAchats()
    {
        return $this->achats;
    }

    public function getTPS()
    {
        return Constantes::TPS;
    }

    public function getTVQ()
    {
        return Constantes::TVQ;
    }
    public function getFraisLivraison()
    {
        $nbArticle = 0;
        foreach ($this->achats as $achat) {
            $nbArticle += $achat->getQuantite();
        }
        if ($nbArticle > 0) {
            return Constantes::FRAIS_LIVRAISON;
        }

        return 0;
    }
    public function estDansLePanier($idProduit)
    {
        foreach ($this->achats as $Lachat) {

            if ($Lachat->getProduit()->getIdProduit() == $idProduit) {
                return true;
            }
        }
        return false;
    }

    public function calculerSousTotalTPS()
    {
        $totalTPS = 0;
        foreach ($this->achats as $achat) {
            if ($achat->getQuantite() > 0)
                $totalTPS += $achat->calculerTotal() * $this->getTPS();
        }
        return $totalTPS;
    }

    public function calculerSousTotalTVQ()
    {
        $totalTVQ = 0;
        foreach ($this->achats as $achat) {
            if ($achat->getQuantite() > 0)
                $totalTVQ += $achat->calculerTotal() * $this->getTVQ();
        }
        return $totalTVQ;
    }
}
