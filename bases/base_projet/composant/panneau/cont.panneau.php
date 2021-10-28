<?php

namespace Controleur;



class Panneau {

    /**
     * Constructeur
     */
    function __construct() {
    }


    /**
     * Destructeur
     */
    function __destruct() {
        require_once 'composant/panneau/vue.panneau.php';
    }

}

?>