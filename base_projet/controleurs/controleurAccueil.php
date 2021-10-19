<?php

// ####################################################################################################
// Inclut les header et footer
require_once 'bas.php';
require_once 'haut.php';
// ####################################################################################################





// ####################################################################################################
// Creation du formulaire
$accueil = new Formulaire('post', 'index.php', 'fAccueil', 'fAccueil');

$accueil->debutDiv('logo');
    $accueil->image('./images/logo.png');  
    $accueil->debutDiv('borgrad');
        $accueil->titre('Votre application fonctionne !');
    $accueil->finDiv();
$accueil->finDiv();



$accueil->titre('Ressources');
$accueil->label("Quelques liens si vous avez besoin d'aide :");
$accueil->debutDiv('wrap');

    $accueil->debutA('https://github.com/TheRake66/Cody-PHP');
        $accueil->image('./images/github.png');  
        $accueil->label('GitHub de Cody-PHP');  
    $accueil->finA();

    $accueil->debutA('https://www.youtube.com/results?search_query=Cody-PHP');
        $accueil->image('./images/youtube.png');  
        $accueil->label('Rechercher sur YouTube');  
    $accueil->finA();

    $accueil->debutA('https://www.google.com/search?q=Cody-PHP');
        $accueil->image('./images/google.png');  
        $accueil->label('Recherche sur Google');  
    $accueil->finA();

    $accueil->debutA('https://github.com/TheRake66/Cody-PHP/documents/');
        $accueil->image('./images/cli.png');  
        $accueil->label('Documentation des commandes');  
    $accueil->finA();

$accueil->finDiv();



$accueil->titre('Commencement');
$accueil->label("Les étapes de base d'un nouveau projet :");
$accueil->debutDiv('wrap');

    $accueil->debutDiv('cd');
        $accueil->image('./images/plus.png');  
        $accueil->label('Changer de dossier');  
    $accueil->finDiv();

    $accueil->debutDiv('ls');
        $accueil->image('./images/plus.png');  
        $accueil->label('Lister les projets existants');  
    $accueil->finDiv();

    $accueil->debutDiv('new');
        $accueil->image('./images/plus.png');  
        $accueil->label('Créer un projet');  
    $accueil->finDiv();

    $accueil->debutDiv('com');
        $accueil->image('./images/plus.png');  
        $accueil->label('Créer un composant');  
    $accueil->finDiv();

    $accueil->debutDiv('obj');
        $accueil->image('./images/plus.png');  
        $accueil->label('Créer un objet');  
    $accueil->finDiv();

$accueil->finDiv();



$accueil->debutDiv('console');
    $accueil->label('0 1 r', 'barre');
    $accueil->label('', 'input');
$accueil->finDiv();



$accueil->debutDiv('icone');

    $accueil->debutA('https://www.facebook.com/TheRake66/');
        $accueil->image('./images/facebook.png');  
    $accueil->finA();

    $accueil->debutA('https://www.instagram.com/therake6666/');
        $accueil->image('./images/instagram.png');  
    $accueil->finA();
    
    $accueil->debutA('https://twitter.com/ThibaultBustos');
        $accueil->image('./images/twitter.png');  
    $accueil->finA();

    $accueil->debutA('https://www.linkedin.com/in/thibault-bustos-6a000a198/');
        $accueil->image('./images/linkedin.png');  
    $accueil->finA();

    $accueil->debutA('https://github.com/TheRake66');
        $accueil->image('./images/github2.png');  
    $accueil->finA();

$accueil->finDiv();

$accueil->build();
// ####################################################################################################





// ####################################################################################################
// Dispatch vers la bonne vue
require_once dispatcher::vue();
// ####################################################################################################