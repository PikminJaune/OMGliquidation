<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\InscriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionControlleurController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {

        $client = new Client();
        $form = $this->createForm(InscriptionFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $client->setPassword(
                $userPasswordHasher->hashPassword(
                    $client,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($client);
            $entityManager->flush();

            // UNE FOIS QUE LE COMPTE EST CRÉER ON SE CONNECTE DIRECTEMENT DESSUS
            $security->login($client);

            return $this->redirectToRoute('app_catalogue');
        }

        return $this->render('inscription_controlleur/index.html.twig', [
            'inscriptionForm' => $form->createView()
        ]);
    }
}
