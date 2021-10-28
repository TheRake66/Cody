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
        require_once 'composant/haut/vue.haut.php';
    }

}

?>