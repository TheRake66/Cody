<?php

namespace Controleur;



class Accueil {

    /*
     * Constructeur
     */
    function __construct() {
    }


    /*
     * Destructeur
     */
    function __destruct() {
        new Haut();
        require_once '././vues/accueil.php';
        new Bas();
    }

}

?>