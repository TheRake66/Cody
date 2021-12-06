<?php

namespace Librairie;


/**
 * Routeur compatible PHP 7 et moins
 */
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
			if (self::routeExiste($_GET['redirect'])) {
				self::$routes[$_GET['redirect']]();
			} else if (isset(self::$notfound)) {
				self::$routes[self::$notfound]();
			} else if (isset(self::$defaut)){
				self::$routes[self::$defaut]();
			} else {
				self::$routes[self::firstKey()]();
			}
		} else if (isset(self::$defaut)) {
			self::$routes[self::$defaut]();
		} else {
			if (count(self::$routes) > 0) {
				self::$routes[self::firstKey()]();
			} else {
				throw new \Exception("Aucune route n'a été définie.");
				die;
			}
		}
	}


    /**
     * Verifi si une route existe
	 * 
	 * @param string nom de la route
	 * @return bool vrai si elle existe
     */
	static function routeExiste($name) {
		foreach (self::$routes as $key => $value) {
			if ($key == $name) return true;
		}
		return false;
	}


    /**
     * Appel la premiere route
     */
	static function firstKey() {
		foreach (self::$routes as $key => $value) {
			return $key;
		}
	}
	
}

?>