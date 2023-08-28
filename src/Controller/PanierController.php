<?php

namespace App\Controller;

use App\Core\Notification;
use App\Core\NotificationColor;
use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    private $panier;
    private $em = null;

    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request): Response
    {
        $this->initSession($request);

        return $this->render('panier/index.html.twig', [
            'panier' => $this->panier,
        ]);
    }


    #[Route('/panier/ajout/{idProduit}', name: 'panier_add', methods: ['POST'])]
    public function addAchatAuPanier($idProduit, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->initSession($request);

        $this->em = $doctrine->getManager();
        $produit = $this->em->getRepository(Produit::class)->find($idProduit);
        $this->panier->ajouterAchat($produit, 1);
        $this->addFlash('panier', new Notification('success', 'Article ajouté avec succèss !', NotificationColor::SUCCESS));

        return $this->redirectToRoute('app_panier');
    }


    #[Route('/panier/supprimer/{idProduit}', name: 'panier_supprimer')]
    public function supprimerAchatPanier($idProduit, Request $request): Response
    {
        $this->initSession($request);

        if ($this->panier->estDansLePanier($idProduit)) {
            $this->panier->supprimerAchat($idProduit);
            $this->addFlash('panier', new Notification('danger', 'Article supprimé avec succèss !', NotificationColor::DANGER));
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/update', name: 'panier_update', methods: ['POST'])]
    public function updatePanier(Request $request): Response
    {
        $post = $request->request->all();
        $this->initSession($request);

        $action = $request->request->get('action');

        if ($action == "rafraichir") {
            $this->panier->update($post);
            $this->addFlash('panier', new Notification('warning', 'Article modifié avec succèss !', NotificationColor::WARNING));
        } else if ($action == "vider") {
            $session = $request->getSession();
            $session->remove('panier');
            $this->addFlash('panier', new Notification('danger', 'Le panier a été vidé.', NotificationColor::DANGER));
        }


        return $this->redirectToRoute('app_panier');
    }

    private function initSession(Request $request)
    {

        $session = $request->getSession();
        $this->panier = $session->get('panier', new Panier());

        $session->set('panier', $this->panier);
        $session->set('panier', $this->panier);
    }
}
