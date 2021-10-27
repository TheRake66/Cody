<?php

namespace Controleur;



class Accueil {

    /**
     * Constructeur
     */
    function __construct() {
    }


    /**
     * Destructeur
     */
    function __destruct() {
        new Haut();
        require_once 'composant/accueil/vue.accueil.php';
        new Bas();
    }

}

?>