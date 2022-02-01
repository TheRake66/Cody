<?php
use Kernel\Router;
use Controleur;



/**
 * La table de routage permet de definir les routes URL pointant
 * vers les controleur. Pour definir une nouvelle route, procedez
 * comme ceci :
 * 
 * r::add('la_route_dans_l'URL', c\le_controleur::class);
 * 
 * 
 * 
 * Pour definir des routes differentes suivant une donnee precise
 * (ex: un role utilisateur), procedez via un if ou un switch :
 * 
 * if (isset($_SESSION['user'])) {
 *     switch ($_SESSION['user']->role) {
 *         case 'eleve':
 *             r::add('accueil', c\Eleve\Accueil::class);
 *             break;
 *         case 'professeur':
 *             r::add('accueil', c\Prof\Accueil::class);
 *             break;
 *         case 'administrateur':
 *             r::add('accueil', c\Admin\Accueil::class);
 *             break;
 *     }
 * } else {
 *     r::add('accueil', c\Accueil::class);
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
 * r::add('accueil', c\Accueil::class);
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
 * r::add('404', c\Notfound::class);
 * 
 * Si aucune route 404 n'est definie, la route par defaut sera utilisee.
 *
 */



// Commencez par creer un composant avec la commande "com -a accueil"
// Router::add('accueil', Controleur\Accueil::class);

?>