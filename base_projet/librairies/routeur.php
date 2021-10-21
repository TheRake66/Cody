<?php

namespace Librairie;



class Routeur {

    /**
     * Liste des routes
     */
	static $routes = [];


    /**
     * Ajoute une route
     */
	static function go($nom, $route) {
		self::$routes[$nom] = $route;
	}


    /**
     * Appel la bonne route
     */
	static function routing() {
		if (isset($_GET['redirect']) && array_key_exists($_GET['redirect'], self::$routes)) {
			self::$routes[$_GET['redirect']]();
		} else {
			self::$routes[array_key_first(self::$routes)]();
		}
	}
	
}

?>