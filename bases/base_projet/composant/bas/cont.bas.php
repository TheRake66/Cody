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
        require_once 'composant/bas/vue.bas.php';
    }

}

?>