<?php

use Librairie\Router as r;
use Controleur as c;



/**
 * La table de routage permet de definir les routes URL pointant
 * vers les controleur. Pour definir une nouvelle route, procedez
 * comme ceci :
 * 
 * r::add('la_route_dans_l'URL', fn() => new c\le_controleur());
 * 
 * 
 * 
 * Pour definir des routes differentes suivant une donnee precise
 * (ex: un role utilisateur), procedez via un if ou un switch :
 * 
 * if (isset($_SESSION['user'])) {
 *     switch ($_SESSION['user']->role) {
 *         case 'eleve':
 *             r::add('accueil', fn() => new c\Eleve\Accueil());
 *             break;
 *         case 'professeur':
 *             r::add('accueil', fn() => new c\Prof\Accueil());
 *             break;
 *         case 'administrateur':
 *             r::add('accueil', fn() => new c\Admin\Accueil());
 *             break;
 *     }
 * } else {
 *     r::add('accueil', fn() => new c\Accueil());
 * }
 * 
 * 
 * 
 * Pour definir la route par defaut, procedez comme ceci :
 * 
 * r::default('accueil');
 * 
 * Puis definissez la route :
 * 
 * r::add('accueil', fn() => new c\Accueil());
 * 
 * Si aucune route par defaut n'est definie, la premiere route definie
 * sera utilisee.
 * 
 * 
 * 
 * Pour definir une route 404 procedez comme ceci :
 *
 * r::notfound('404');
 * 
 * Puis definissez la route :
 * 
 * r::add('404', fn() => new c\Notfound());
 * 
 * Si aucune route 404 n'est definie, la route par defaut sera utilisee.
 *
 */


r::add('accueil', fn() => new c\Accueil());


?>