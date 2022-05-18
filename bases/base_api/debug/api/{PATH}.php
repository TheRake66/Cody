<?php
namespace API{NAMESPACE_SLASH};

use Kernel\Communication\Rest;
use Kernel\DataBase\Factory\Crud;
use Kernel\Security\Vulnerability\XSS;
use Kernel\Security\Vulnerability\CSRF;
use Kernel\Security\Validation;



/**
 * Module d'API {NAME_UPPER}
 * 
 * @author {USER_NAME}
 * @version 1.0
 * @package API{NAMESPACE_SLASH}
 * @category API
 */
class {NAME_UPPER} extends Rest {

    /**
     * Appel via la méthode GET
     * 
     * @param array $params Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function get($param) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode POST
     * 
     * @param array $params Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function post($param) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode PUT
     * 
     * @param array $params Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function put($param) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode DELETE
     * 
     * @param array $params Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function delete($param) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode PATCH
     * 
     * @param array $params Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function patch($param) {
        Error::trigger('Fonction non implémentée');
    }

}

?>