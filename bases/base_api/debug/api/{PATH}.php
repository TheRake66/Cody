<?php
namespace API{NAMESPACE_SLASH};

use Kernel\Debug\Error;
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
     * @param array $route Paramètres de la route
     * @param array $query Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function get($route, $query) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode POST
     * 
     * @param array $route Paramètres de la route
     * @param array $query Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function post($route, $query) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode PUT
     * 
     * @param array $route Paramètres de la route
     * @param array $query Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function put($route, $query) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode DELETE
     * 
     * @param array $route Paramètres de la route
     * @param array $query Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function delete($route, $query) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode PATCH
     * 
     * @param array $route Paramètres de la route
     * @param array $query Paramètres de la requête
     * @return mixed Résultat de l'appel
     */
    function patch($route, $query) {
        Error::trigger('Fonction non implémentée');
    }

}

?>