<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{

    private $em = null;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    #[Route('/stripe-checkout', name: 'stripe_checkout')]
    public function stripeCheckout(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //Nous sommes connectés
        $user = $this->getUser();

        $session = $request->getSession();
        $panier = $session->get('panier');


        \Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $successURL = $this->generateUrl('stripe_success', [], UrlGeneratorInterface::ABSOLUTE_URL) . "?stripe_id={CHECKOUT_SESSION_ID}";

        $sessionData = [
            'line_items' => [[
                'quantity' => 1,
                'price_data' => ['unit_amount' => intval($panier->caculerGrandTotal() * 100), 'currency' => 'CAD', 'product_data' => ['name' => 'Magasin en ligne OMG Liquidation']]
            ]],
            'customer_email' => $user->getCourriel(),
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => $successURL,
            'cancel_url' => $this->generateUrl('stripe_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ];

        //Extension curl nécessaire 
        $checkoutSession = \Stripe\Checkout\Session::create($sessionData);
        return $this->redirect($checkoutSession->url, 303);
    }


    #[Route('/stripe-success', name: 'stripe_success')]
    public function stripeSuccess(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $message = "";
        $client = $this->getUser();
        // 1. Retrouver la panier en session
        $session = $request->getSession();
        $panier = $session->get('panier');

        try {
            $stripe = new \Stripe\StripeClient($_ENV["STRIPE_SECRET"]);
            $stripeSessionId = $request->query->get('stripe_id');
            $sessionStripe = $stripe->checkout->sessions->retrieve($stripeSessionId);
            $paymentIntent = $sessionStripe->payment_intent;

            // 2. Création de la commande 
            $commande = new Commande($client, $panier, $paymentIntent);

            // 3 . Boucle sur les Achats 
            foreach ($panier->getAchats() as $achat) {
                // 3.1 Merge produit 
                $produit = $this->em->merge($achat->getProduit());
                // 3.2 vendre x produit ( retirer en BD le nombre de produits vendu à leurs quantités)
                $produit->enleverQuantite($achat->getQuantite());
                // TODO : Fix ce bug 
                // if ($produit->enleverQuantite($achat->getQuantite()) < 0) {
                //     $message += "L'article {{$produit->nom}} n'est plus en stock et vous sera envoyé dès qu'il sera reçu.<br>";
                // }
                // 3.3 Achat -> setproduit(p)
                $achat->setProduit($produit);
            }

            // 4. Persis/Flush la commande
            $this->em->persist($commande);
            $this->em->flush();
        } catch (\Exception $e) {
            return $this->redirectToRoute('app_panier');
        }

        // 5 . Vider le panier 
        $session->remove('panier');
        // Source de retour de la page : https://stackoverflow.com/questions/53758031/symfony-4-how-to-set-entity-id-for-route-in-redirect-after-form-submit
        return $this->redirectToRoute('app_profil_commande_id', ['idCommande' => $commande->getIdCommande()]);
    }

    #[Route('/stripe-cancel', name: 'stripe_cancel')]
    public function stripeCancel(): Response
    {
        return $this->redirectToRoute('app_profil');
    }
}
