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
 */
class {NAME_UPPER} extends Render {

    /**
     * Constructeur
     */
    function __construct() {
        // Rendu de la vue
        $this->view();
    }

}

?>