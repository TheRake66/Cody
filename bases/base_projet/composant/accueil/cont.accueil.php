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
        include 'composant/accueil/vue.accueil.php';
    }

}

?>