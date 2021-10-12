<?php

// ####################################################################################################
// Creation du formulaire
$accueil = new Formulaire('post', 'index.php', 'fAccueil', 'fAccueil');
$accueil->ajouterComposantLigne($accueil->creerParagh('Le fomulaire "Accueil" fonctionne !'));
$accueil->ajouterComposantTab();
$accueil->creerFormulaire();
// ####################################################################################################





// ####################################################################################################
// Dispatch vers la bonne vue
require_once dispatcher::vue();
// ####################################################################################################