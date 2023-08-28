<?php

namespace App\Controller;

use App\Core\Notification;
use App\Core\NotificationColor;
use App\Entity\Commande;
use App\Form\ChangerEtatCommandeType;
use App\Form\ModificationFormType;
use App\Form\ModifierMotDePasseFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function indexProfil(Request $request): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUser = $this->getUser();

        $form = $this->createForm(ModificationFormType::class, $currentUser);
        $form->handleRequest($request);

        $formMDP = $this->createForm(ModifierMotDePasseFormType::class);
        $formMDP->handleRequest($request);

        return $this->render('profil/index.html.twig', [
            'currentUser' => $currentUser,
            'modificationForm' => $form->createView(),
            'modificationMdpForm' => $formMDP->createView()
        ]);
    }

    #[Route('/profil/commandes', name: 'app_profil_commandes')]
    public function resumerCommande(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('commande/index.html.twig', [
            'currentUser' => $this->getUser(),
        ]);
    }

    #[Route('/profil/commandes/{idCommande}', name: 'app_profil_commande_id')]
    public function resumerUneCommande($idCommande, ManagerRegistry $doctrine, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $doctrine->getManager();
        $commande = $em->getRepository(Commande::class)->find($idCommande);

        $formModifEtat = $this->createForm(ChangerEtatCommandeType::class);
        $formModifEtat->handleRequest($request);

        if ($formModifEtat->isSubmitted() && $formModifEtat->isValid()) {
            $etat = $formModifEtat->getData();
            $commande->setEtat($etat["etat"]);
            $em->persist($commande);
            $em->flush();
        }


        if (!$commande) {
            return $this->redirectToRoute('app_profil_commandes');
        } else {
            return $this->render('commande/details.html.twig', [
                'commande' => $commande,
                'formModifEtat' => $formModifEtat
            ]);
        }
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_profil');
        }

        $notification = null;
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error != null && $error->getMessageKey() === 'Invalid credentials.') {
            $message = "Mauvais courriel et/ou mot de passe";
            $notification = new Notification('erreurConnexion', $message, NotificationColor::WARNING);
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('profil/login.html.twig', [
            'last_username' => $lastUsername,
            'notification' => $notification
        ]);
    }

    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function deconnexion(): Response
    {
        throw new \Exception("Deconnexion");
    }
}
