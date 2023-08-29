# OMGliquidation
Un projet web e-commerce qui a été plaisant à faire a l'école.Ce projet a été fait dans le cours de Développement Web au Cégep de Saint-Jérôme.Mon site est un site de vente d'électro ménager fictif.Toutes les images viennent du site d'economax.Les captures d'écrans montrent une vue de compte admin , donc il ya a des fonctionnalités admin qu'un simple utilisateur ne peux pas voir.Par contre , on va pouvoir voir la base du site qu'un utilisateur régulier pourrait faire comme par exemple ajouter des items dans le panier, voir son profil , voir l'historique de ses commandes , etc.Tout les informations qu'on voit dans le projet ( produits , comptes , commandes , etc ) sont stockées dans une BD.

# <h1 align="center">L'accueil</h1>

Plutôt classique comme page d'entré , le menu a droite est les catégorie donc si on click dessus un filtre se créer et la catégorie cliquer sera afficher sur la page d'accueil.On voit les articles qui sont en stock et leurs quantités qu'on peut ajouter dans le panier en cliquant "ajouter".Si un article n'a pas d'image un image par défault est ajoutée.On remarque aussi que si l'article n'est pas en stock il n'y a pas de bouton d'ajout dans le panier.il y a une barre de recherche en haut qui est disponible en tout temps.Elle fait une recherche dans la BD avec les critères choisis.Exemple wi-fi qui retourne les articles qui on comme description le mot wi-fi.

![pagePrincipale](https://github.com/PikminJaune/OMGliquidation/assets/71794298/fabac7e4-f6a4-4560-9573-7016ed37a8bd)

En cliquant sur l'image du produit un pop-up apparait du détail de l'article.

![unProduitDetail](https://github.com/PikminJaune/OMGliquidation/assets/71794298/29fd1411-5f18-4a5f-a622-b047508c5030)

# <h1 align="center">Contact</h1>

Cette page est simplement une page statique qui donne de l'information sur le projet.

![pageContact](https://github.com/PikminJaune/OMGliquidation/assets/71794298/a81c0286-9cb3-4d2f-9003-370725b6f04a)

# <h1 align="center">Panier</h1>

Une grosse partie du code est ici.Tout les articles qui seront ajoutés au panier se retrouve ici.À droite on peux voir le sommaire de la facture.En bas des article le bouton "mise à jour" met a jour la quantité de produit dans le panier si celle-ci a été modifiée.Le bouton "vider le panier" lui comme il le dit vide tout ce qu'il y a dans la liste panier.Une fois qu'on est prêt a payer un clique sur "Commander" à droite , mais attention ! il faut être connecté pour pouvoir commander.Si le client n'est pas connecté ça le redirige directement sur la partie connexion que nous allons voir plus tard.Il peut aussi se créer un compte au besoin.

![pagePanier](https://github.com/PikminJaune/OMGliquidation/assets/71794298/ff8576f0-b75a-4201-aaa1-421fab97fdcb)


Par la suite , on doit confirmer notre achat et ça nous apporte sur la page de paiement stripe qui , dans cette capture d'écran , est en mode dev.

![paiementStripeCommande](https://github.com/PikminJaune/OMGliquidation/assets/71794298/22fbaf65-cee0-4248-a136-a0c0cb94967e)

une fois le paiement validé par Stripe nous retournons sur le site.

# <h1 align="center">Création de compte et connexion</h1>

Voici la section pour se créer un compte.Toutes les informations rentré dans le formulaire seront validé par une fonction JavaScript et si un petit malin le désactive dans le fureteur aucun soucis il y a une double vérification avant d'être ajouté en BD.Une fois le compte créer , nous sommes directement connecté.

![pageInscription](https://github.com/PikminJaune/OMGliquidation/assets/71794298/78995c31-4cbe-4eb6-96bd-74883f9a5554)

Si jamais le client possède déjà un compte , il peut tout simplement se connecter avec ses identifiants.

![pageConnexion](https://github.com/PikminJaune/OMGliquidation/assets/71794298/1d623bd8-091f-403e-a6e7-f222628bd33a)

# <h1 align="center">Profil</h1>

Lors de la connexion , le site nous redirige sur cette page.À gauche toutes les informations du clients , qu'il peut modifier quand qu'il veut sauf pour son courriel qui reste le même.Le changement de mots de passe ce fait ici aussi.À droite les commandes faites par le client.

![profilUtilisateur](https://github.com/PikminJaune/OMGliquidation/assets/71794298/0470e7c4-b859-488a-b1cb-aeee68224d48)

on peut cliquer sur l'oeil à droite de la commande dans la section "détails" pour voir tout ses détails.Pour que ce sois plus simple à présenter nous allons voir les détails vue d'un admin qui peux change l'état mais , _notez bien qu'un utilisateur normal ne peut pas changer l'état de sa commande._

![detailsCommande](https://github.com/PikminJaune/OMGliquidation/assets/71794298/e737762b-3f2a-471d-a9dc-2239b7286fad)

# <h1 align="center">Les admins</h1>

Les admins ont plusieurs fonctions :
- Ajouter un produit.
- Modifier un produit.
- Modifier une catégorie.
- Ajouter une catégorie.
- Voir la liste de produits.
- Voir toutes les commandes effectuées.

Une double vérification est faite.Si aucune image n'est chosie , il y en aura une par défault.
![ajoutProduit](https://github.com/PikminJaune/OMGliquidation/assets/71794298/5cfcac72-186f-4e8a-9190-88cc81878eac)
![modifierProduit](https://github.com/PikminJaune/OMGliquidation/assets/71794298/2f8f8494-cbe2-4dfe-abb2-013260fd490b)
![adminModifCatégorie](https://github.com/PikminJaune/OMGliquidation/assets/71794298/1ac2f39b-bbf2-4844-94e6-5cb70f7bb7cd)
![listeProduit](https://github.com/PikminJaune/OMGliquidation/assets/71794298/e743e14a-3c22-4774-83c8-1fc8cff3467d)
![listeCommandes](https://github.com/PikminJaune/OMGliquidation/assets/71794298/887aa46f-bae6-458c-ae89-973a93cc78d3)
