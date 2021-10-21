<?php

namespace Controleur;



class {NAME_UPPER} {

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
        require_once '././vues/{NAME_LOWER}.php';
        new Bas();
    }

}

?>