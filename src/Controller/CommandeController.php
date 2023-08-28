<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    private $panier;
    #[Route('/commandes', name: 'app_resumer_commande')]
    public function resumerCommande(Request $request): Response
    {
        $this->initSession($request);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (count($this->panier->getAchats()) == 0) {
            return $this->redirectToRoute('app_panier');
        }

        return $this->render('commande/confirmation.html.twig', [
            'panier' => $this->panier,
        ]);
    }

    private function initSession(Request $request)
    {

        $session = $request->getSession();
        $this->panier = $session->get('panier');

        $session->set('panier', $this->panier);
        $session->set('panier', $this->panier);
    }
}
