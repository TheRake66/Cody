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
     * @param string $route La route de l'appel
     * @param array $query Paramètres de la route
     * @param array $body Corps de la requête
     * @return mixed Résultat de l'appel
     */
    function get($route, $query, $body) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode POST
     * 
     * @param string $route La route de l'appel
     * @param array $query Paramètres de la route
     * @param array $body Corps de la requête
     * @return mixed Résultat de l'appel
     */
    function post($route, $query, $body) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode PUT
     * 
     * @param string $route La route de l'appel
     * @param array $query Paramètres de la route
     * @param array $body Corps de la requête
     * @return mixed Résultat de l'appel
     */
    function put($route, $query, $body) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode DELETE
     * 
     * @param string $route La route de l'appel
     * @param array $query Paramètres de la route
     * @param array $body Corps de la requête
     * @return mixed Résultat de l'appel
     */
    function delete($route, $query, $body) {
        Error::trigger('Fonction non implémentée');
    }


    /**
     * Appel via la méthode PATCH
     * 
     * @param string $route La route de l'appel
     * @param array $query Paramètres de la route
     * @param array $body Corps de la requête
     * @return mixed Résultat de l'appel
     */
    function patch($route, $query, $body) {
        Error::trigger('Fonction non implémentée');
    }

}

?>