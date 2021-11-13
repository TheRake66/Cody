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
        include 'composant/menu/vue.menu.php';
    }

}

?>