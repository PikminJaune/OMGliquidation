// J'ai essayé plein d'affaire et c'est la seule chose qui fonctionnait pour moi 
$('#changer_etat_commande_etat').change(function () {
    document.getElementById("changementEtatForm").submit();
});