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
        include 'composant/panneau/vue.panneau.php';
    }

}

?>