<?php
namespace Controler{NAMESPACE_SLASH};
use Kernel\DataBase\Factory\Crud;
use Kernel\Security\Vulnerability\XSS;
use Kernel\Security\Vulnerability\CSRF;
use Kernel\Security\Validation;
use Kernel\Rest;
use Kernel\Render;



/**
 * Controleur du composant {NAME_UPPER}
 * 
 * @author {USER_NAME}
 * @version 1.0
 * @package Controler{NAMESPACE_SLASH}
 * @category Controleur
 */
class {NAME_UPPER} extends Render {

    /**
     * Point d'entrée du controleur
     * 
     * @access public
     * @return void
     */
    function __construct() {
        // Rendu du composant
        $this->view();
    }

}

?>