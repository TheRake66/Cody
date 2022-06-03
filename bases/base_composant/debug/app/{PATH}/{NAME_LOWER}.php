<?php
namespace Controller{NAMESPACE_SLASH};
use Kernel\Security\Vulnerability\Xss;
use Kernel\Security\Vulnerability\Csrf;
use Kernel\Security\Validation;
use Kernel\Io\Render;



/**
 * Controleur du composant {NAME_UPPER}
 * 
 * @author {USER_NAME}
 * @version 1.0
 * @package Controller{NAMESPACE_SLASH}
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