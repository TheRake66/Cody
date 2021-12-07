<?php
// Librairie Router
namespace Kernel;



class Router {

    /**
     * Liste des routes
     */
	private static $routes = [];

    /**
     * Route par defaut
     */
	private static $default;

    /**
     * Route si non trouve
     */
	private static $notfound;


    /**
     * Configure la route par defaut
	 * 
	 * @param string nom de la route
     */
	static function default($defaut) {
		self::$default = $defaut;
	}


    /**
     * Configure la route en cas de route non trouvee (404)
	 * 
	 * @param string nom de la route
     */
	static function notfound($notfound) {
		self::$notfound = $notfound;
	}


    /**
     * Ajoute une route
	 * 
	 * @param string nom de la route
	 * @param function fonction anonyme contenant la route
     */
	static function add($nom, $route) {
		self::$routes[$nom] = $route;
	}
	

	/**
	 * Retourne la route actuelle
	 * 
	 * @return string le nom de la route
	 */
	static function get() {
		return $_GET['redirect'] ?? null;
	}


	/**
	 * Retourne la premiere route
	 * 
	 * @return string nom de la premiere route
	 */
	static function getFirst() {
		if (count(self::$routes) > 0) {
			return array_key_first(self::$routes);
		}
	}


	/**
	 * Verifi si une route existe
	 * 
	 * @param string nom de la route
	 * @return bool si existe
	 */
	static function exist($name) {
		return (!is_null($name) && array_key_exists($name, self::$routes));
	}


    /**
     * Appel la bonne route
     */
	static function routing() {
		require_once 'composant/route.php';

		$r = null;
		if (isset($_GET['redirect'])) {
			if (self::exist($_GET['redirect'])) {
				$r = $_GET['redirect'];
			} elseif(self::exist(self::$notfound)) {
				$r = self::$notfound;
			}
		}
		if (is_null($r)) {
			if(self::exist(self::$default)) {
				$r = self::$default;
			} else {
				$r = self::getFirst();
				if (is_null($r)) {
					trigger_error("Aucune route n'a été définie.");
					die;
				}
			}
		}

		self::$routes[$r]();
	}
	
}

?>