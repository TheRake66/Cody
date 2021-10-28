<?php

namespace Controleur;



class Menu {

    /**
     * Constructeur
     */
    function __construct() {
    }


    /**
     * Destructeur
     */
    function __destruct() {
        require_once 'composant/menu/vue.menu.php';
    }

}

?>