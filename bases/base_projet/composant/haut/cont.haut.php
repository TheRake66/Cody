<?php

namespace Controleur;



class Haut {

    /**
     * Constructeur
     */
    function __construct() {
    }


    /**
     * Destructeur
     */
    function __destruct() {
        include 'composant/haut/vue.haut.php';
    }

}

?>