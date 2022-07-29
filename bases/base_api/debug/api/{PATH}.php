<?php
namespace Api{NAMESPACE_SLASH};

use Kernel\Debug\Error;
use Kernel\Communication\Rest;
use Kernel\Security\Vulnerability\Xss;
use Kernel\Security\Vulnerability\Csrf;
use Kernel\Security\Validation;



/**
 * Module d'API {NAME_UPPER}.
 * 
 * @author {USER_NAME}
 * @version 1.0
 * @package Api{NAMESPACE_SLASH}
 * @category API module
 */
class {NAME_UPPER} extends Rest {

    /**
     * Appel via la méthode GET.
     * 
     * @param object $route Les paramètres de la route.
     * @param object $query Les paramètres de la requête.
     * @param object $body Le corps de la requête.
     * @return mixed Résultat de l'appel.
     */
    function get($route, $query, $body) {
        $this->send(null, 1, 'Méthode GET non implémentée !', 500);
    }


    /**
     * Appel via la méthode POST.
     * 
     * @param object $route Les paramètres de la route.
     * @param object $query Les paramètres de la requête.
     * @param object $body Le corps de la requête.
     * @return mixed Résultat de l'appel.
     */
    function post($route, $query, $body) {
        $this->send(null, 1, 'Méthode POST non implémentée !', 500);
    }


    /**
     * Appel via la méthode PUT.
     * 
     * @param object $route Les paramètres de la route.
     * @param object $query Les paramètres de la requête.
     * @param object $body Le corps de la requête.
     * @return mixed Résultat de l'appel.
     */
    function put($route, $query, $body) {
        $this->send(null, 1, 'Méthode PUT non implémentée !', 500);
    }


    /**
     * Appel via la méthode DELETE.
     * 
     * @param object $route Les paramètres de la route.
     * @param object $query Les paramètres de la requête.
     * @param object $body Le corps de la requête.
     * @return mixed Résultat de l'appel.
     */
    function delete($route, $query, $body) {
        $this->send(null, 1, 'Méthode DELETE non implémentée !', 500);
    }


    /**
     * Appel via la méthode PATCH.
     * 
     * @param object $route Les paramètres de la route.
     * @param object $query Les paramètres de la requête.
     * @param object $body Le corps de la requête.
     * @return mixed Résultat de l'appel.
     */
    function patch($route, $query, $body) {
        $this->send(null, 1, 'Méthode PATCH non implémentée !', 500);
    }

}

?>