<?php
namespace Api{NAMESPACE_SLASH};

use Kernel\Debug\Error;
use Kernel\Communication\Rest;
use Kernel\Security\Vulnerability\Xss;
use Kernel\Security\Vulnerability\Csrf;
use Kernel\Security\Validation;



/**
 * Module d'API {NAME_UPPER}
 * 
 * @author {USER_NAME}
 * @version 1.0
 * @package Api{NAMESPACE_SLASH}
 * @category API
 */
class {NAME_UPPER} extends Rest {

    /**
     * Appel via la methode GET
     * 
     * @param string la route de l'appel
     * @param array les parametres de la route
     * @param array le corps de la requête
     * @return mixed resultat de l'appel
     */
    function get($route, $query, $body) {
        $this->send(null, 0, 'Fonction non implémentée !', 500);
    }


    /**
     * Appel via la methode POST
     * 
     * @param string la route de l'appel
     * @param array les parametres de la route
     * @param array le corps de la requête
     * @return mixed resultat de l'appel
     */
    function post($route, $query, $body) {
        $this->send(null, 0, 'Fonction non implémentée !', 500);
    }


    /**
     * Appel via la methode PUT
     * 
     * @param string la route de l'appel
     * @param array les parametres de la route
     * @param array le corps de la requête
     * @return mixed resultat de l'appel
     */
    function put($route, $query, $body) {
        $this->send(null, 0, 'Fonction non implémentée !', 500);
    }


    /**
     * Appel via la methode DELETE
     * 
     * @param string la route de l'appel
     * @param array les parametres de la route
     * @param array le corps de la requête
     * @return mixed resultat de l'appel
     */
    function delete($route, $query, $body) {
        $this->send(null, 0, 'Fonction non implémentée !', 500);
    }


    /**
     * Appel via la methode PATCH
     * 
     * @param string la route de l'appel
     * @param array les parametres de la route
     * @param array le corps de la requête
     * @return mixed resultat de l'appel
     */
    function patch($route, $query, $body) {
        $this->send(null, 0, 'Fonction non implémentée !', 500);
    }

}

?>