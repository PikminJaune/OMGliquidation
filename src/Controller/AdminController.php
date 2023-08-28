<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Form\CategorieAdminFormType;
use App\Form\CategorieCollection;
use App\Form\CategorieCollectionType;
use App\Form\ModificationProduitType;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class AdminController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // ROUTE POUR VOIR TOUTES LES CATÉGORIES ET LES MODIFIER/AJOUTER
    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function modifierCategoriesAdmin(Request $request): Response
    {

        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_catalogue');
        }

        $categories = $this->em->getRepository(Categorie::class)->findAll();
        $categorieCollection = new CategorieCollection($categories);

        $formCategories = $this->createForm(CategorieCollectionType::class, $categorieCollection);
        $formCategories->handleRequest($request);


        if ($formCategories->isSubmitted() && $formCategories->isValid()) {
            $newCollectionCategories = $formCategories->getData()->getCategories();


            foreach ($newCollectionCategories as $newCategorie) {
                $this->em->persist($newCategorie);
            }
            $this->em->flush();
        }



        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
            'categorieForm' => $formCategories->createView()
        ]);
    }

    // ROUTE NOUVEAU PRODUIT
    #[Route('/admin/nouveauProduit', name: 'app_admin_nouveauproduit')]
    public function ajoutProduitAdmin(Request $request, SluggerInterface $slugger): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_catalogue');
        }

        $produit = new Produit();
        $formNouveauProduit = $this->createForm(ProduitType::class, $produit);
        $formNouveauProduit->handleRequest($request);


        if ($formNouveauProduit->isSubmitted() && $formNouveauProduit->isValid()) {

            $imagePath = $formNouveauProduit->get('imagePath')->getData();

            if ($imagePath) {
                $originalFilename = pathinfo($imagePath->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . "-" . uniqid() . "." . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('image_produit'),
                        $newFilename
                    );

                    $produit->setImagePath("images/produits/" . $newFilename);
                } catch (FileException $e) {
                    //TODO: Erreur
                } catch (ORMException $e) {
                    //TODO: Erreur
                }
            } else {
                $produit->setImagePath("images/produits/sansphoto.png");
            }
            $this->em->persist($produit);
            $this->em->flush();
        }

        return $this->render('admin/nouveauproduit.html.twig', [
            'nouveauProduitForm' => $formNouveauProduit->createView()
        ]);
    }

    //ROUTE POUR VOIR TOUT LES PRODUITS 
    #[Route('/admin/produits', name: 'app_admin_produits')]
    public function toutProduits(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_catalogue');
        }

        $produits = $this->em->getRepository(Produit::class)->findAll();

        return $this->render('admin/produits.html.twig', [
            'toutsProduits' => $produits
        ]);
    }

    //ROUTE POUR VOIR MODIFIER UN PRODUIT 
    #[Route('/admin/produits/{idProduit}', name: 'app_admin_modif_produit')]
    public function modifierUnProduit($idProduit, Request $request, SluggerInterface $slugger): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_catalogue');
        }

        $produit = $this->em->getRepository(Produit::class)->find($idProduit);
        // on get l'image tout de suite sinon elle va etre null une fois passé dans le form
        $photoMaintenant = $produit->getImagePath();
        $formModificationProduit = $this->createForm(ModificationProduitType::class, $produit);
        $formModificationProduit->handleRequest($request);

        if ($formModificationProduit->isSubmitted() && $formModificationProduit->isValid()) {

            $nouvellePhoto = $formModificationProduit->get('imagePath')->getData();

            if ($nouvellePhoto) {
                $originalFilename = pathinfo($nouvellePhoto->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . "-" . uniqid() . "." . $nouvellePhoto->guessExtension();

                try {
                    $nouvellePhoto->move(
                        $this->getParameter('image_produit'),
                        $newFilename
                    );

                    $produit->setImagePath("images/produits/" . $newFilename);
                } catch (FileException $e) {
                    //TODO: Erreur
                } catch (ORMException $e) {
                    //TODO: Erreur
                }
            } else {
                $produit->setImagePath($photoMaintenant);
            }

            $this->em->getRepository(Produit::class)->save($produit, true);
            return $this->redirectToRoute('app_admin_produits');
        }



        return $this->render('admin/modifierProduit.html.twig', [
            'form_modif_produit' => $formModificationProduit
        ]);
    }

    // ROUTE POUR VOIR TOUTES LES COMMANDES ET MODIFIER LE STATUS
    #[Route('/admin/commandes', name: 'app_admin_commandes')]
    public function toutesCommandes(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_catalogue');
        }

        $commandes = $this->em->getRepository(Commande::class)->findAll();

        // On envoie les commandes en l'envers donc la derniere commande créée en premier.
        $commandes = array_reverse($commandes);

        return $this->render('admin/commandes.html.twig', [
            'toutesCommandes' => $commandes
        ]);
    }
}
