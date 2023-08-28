<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ORM\Table(name: 'categories')]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idCategorie')]
    private ?int $idCategorie = null;

    #[ORM\Column(length: 30)]
    private ?string $categorie = null;

    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: "categorie", fetch: "LAZY")]
    private $produits;

    public function getIdCategorie(): ?int
    {
        return $this->idCategorie;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
