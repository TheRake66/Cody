<?php

namespace Controleur;



class Bas {

    /**
     * Constructeur
     */
    function __construct() {
    }


    /**
     * Destructeur
     */
    function __destruct() {
        include 'composant/bas/vue.bas.php';
    }

}

?>