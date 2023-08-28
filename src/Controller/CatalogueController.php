<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CatalogueController extends AbstractController
{
    private $em = null;

    #[Route('/', name: 'app_catalogue')]
    public function indexRoute(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->em = $doctrine->getManager();

        $categorie = $request->query->get('cat'); // le get utilise le nom dans le boucle qui va etre mis dans le lien du site 
        $searchField = $request->request->get('search_field'); // le search_field est le nom du input serach qu'on a fais dans la navbar , ça doit être le même nom 
        $produits = $this->retrieveProduits($categorie, $searchField);

        // POUR AFFICHER TOUTES LES CATÉGORIES ET LES PRODUITS 
        $categories = $this->retrieveAllCategories();

        return $this->render('catalogue/catalogue.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
        ]);
    }

    #[Route('/produits/{idProduit}', name: 'produit_modal')]
    public function informationProduit($idProduit, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->em = $doctrine->getManager();

        $produit = $this->em->getRepository(Produit::class)->find($idProduit);

        return $this->render('home/produit.modal.twig', ['produit' => $produit]);
    }


    // Inutile pour le moment puisqu'on met des filtre avec la methode retrieveProduits
    private function retrieveAllProduits()
    {

        return $this->em->getRepository(Produit::class)->findAll();
    }

    private function retrieveAllCategories()
    {

        return $this->em->getRepository(Categorie::class)->findAll();
    }

    private function retrieveProduits($categorie, $searchField)
    {
        return $this->em->getRepository(Produit::class)->findWithCriteria($categorie, $searchField);
    }
}
