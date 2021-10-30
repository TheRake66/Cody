<?php

namespace Librairie;



class Routeur {

    /**
     * Liste des routes
     */
	static $routes = [];

    /**
     * Route par defaut
     */
	static $defaut;

    /**
     * Route si non trouve
     */
	static $notfound;


    /**
     * Configure la route par defaut
	 * 
	 * @param string nom de la route
     */
	static function defaut($defaut) {
		self::$defaut = $defaut;
	}


    /**
     * Configure la route en cas de route non trouvee (404)
	 * 
	 * @param string nom de la route
     */
	static function introuvable($notfound) {
		self::$notfound = $notfound;
	}


    /**
     * Ajoute une route
	 * 
	 * @param string nom de la route
	 * @param function fonction anonyme contenant la route
     */
	static function go($nom, $route) {
		self::$routes[$nom] = $route;
	}


    /**
     * Appel la bonne route
     */
	static function routing() {
		if (isset($_GET['redirect'])) {
			if (array_key_exists($_GET['redirect'], self::$routes)) {
				self::$routes[$_GET['redirect']]();
			} else if (isset(self::$notfound)) {
				self::$routes[self::$notfound]();
			} else if (isset(self::$defaut)){
				self::$routes[self::$defaut]();
			} else {
				self::$routes[array_key_first(self::$routes)]();
			}
		} else if (isset(self::$defaut)) {
			self::$routes[self::$defaut]();
		} else {
			self::$routes[array_key_first(self::$routes)]();
		}
	}
	
}

?>