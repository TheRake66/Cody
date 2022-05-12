<?php
namespace Kernel;



/**
 * Librairie gerant le routage des pages
 */
class Router {

    /**
	 * @var array Liste des routes [ string => class ]
     */
	private static $routes = [];

    /**
	 * @var string Route par defaut
     */
	private static $default;

    /**
	 * @var string Route si non trouve
     */
	private static $notfound;

    /**
	 * @var string Route actuelle
     */
	private static $current;


	/**
	 * Charge les routes
	 * 
	 * @return void
	 * @throws Error si le fichier de route n'est pas trouvé
	 */
	static function load() {
		Path::require('debug/app/route.php', true);
	}


    /**
     * Definie la route par defaut
	 * 
	 * @param string la route
	 * @return void
     */
	static function default($defaut) {
		self::$default = $defaut;
	}


    /**
     * Definie la route en cas de route non trouvee (404)
	 * 
	 * @param string la route
	 * @return void
     */
	static function notfound($notfound) {
		self::$notfound = $notfound;
	}


    /**
     * Ajoute une route
	 * 
	 * @param string la route
	 * @param object classe du controleur
	 * @return void
     */
	static function add($nom, $route) {
		self::$routes[$nom] = $route;
	}
	

	/**
	 * Retourne la route actuelle
	 * 
	 * @return string la route
	 * @throws Error si aucune route n'a ete definie
	 */
	static function getCurrent() {
		if (is_null(self::$current)) {
			$route = null;
			$asked = self::getAsked();
			if (!is_null($asked)) {
				$route = self::whoMatch($asked);
				if (is_null($route)) {
					if (self::whoMatch(self::$notfound)) {
						http_response_code(404);
						$route = self::$notfound;
					} elseif (self::whoMatch(self::$default)) {
						$route = self::$default;
					} else {
						$route = self::getFirst();
					}
				}
			} elseif (self::whoMatch(self::$default)) {
				$route = self::$default;
			} else {
				$route = self::getFirst();
			}
			if (is_null($route)) {
				Error::trigger('Aucune route n\'a ete definie !');
			}
			self::$current = $route;
			return $route;
		} else {
			return self::$current;
		}
	}


	/**
	 * Retourne la route correspondante a une route demandee
	 * 
	 * @param string l'url
	 * @return string la route ou null si aucune correspondance
	 */
	static function whoMatch($asked) {
		if (!is_null($asked)) {
			$split_asked = explode('/', $asked);
			foreach (self::$routes as $route => $controler) {
				$split_route = explode('/', $route);
				if (count($split_route) == count($split_asked)) {
					$i = 0;
					$match = true;
					$params = [];
					while ($i < count($split_asked) && $i < count($split_route) && $match) {
						$word_asked = $split_asked[$i];
						$word_route = $split_route[$i];
						if (!empty($word_asked) && !empty($word_route)) {
							if (substr($word_route, 0, 1) == ':') {
								$params[substr($word_route, 1)] = $word_asked;
							} elseif ($word_route != $word_asked) {
								$match = false;
							}
						}
						$i++;
					}
					if ($match) {
						$GLOBALS['_ROUTE'] = $params;
						return $route;
					}
				}
			}
		}
	}


	/**
	 * Retourne la route demandee
	 * 
	 * @return string le route demandee
	 */
	static function getAsked() {
		if (isset($_SERVER['PATH_INFO'])) {
			return$_SERVER['PATH_INFO'];
		} elseif (isset($_SERVER['REDIRECT_URL'])) {
			return substr($_SERVER['REDIRECT_URL'], strlen($_SERVER['REDIRECT_BASE']));
		}
	}
	

	/**
	 * Retourne le controleur de la route actuelle
	 * 
	 * @return object le controleur
	 */
	static function getController() {
		return self::$routes[self::getCurrent()];
	}


	/**
	 * Retourne la premiere route
	 * 
	 * @return string la premiere route
	 */
	static function getFirst() {
		if (count(self::$routes) > 0) {
			return array_keys(self::$routes)[0];
		}
	}


    /**
     * Appel le controleur de la route demandée
	 * 
	 * @return void
     */
	static function routing() {
        Debug::log('Routage (url : "' . Url::getCurrent() . '")...', Debug::LEVEL_PROGRESS);

		$c = self::getController();
        Debug::log('Contrôleur identifié : "' . $c . '".');

		new $c();
		Debug::log('Routage fait.', Debug::LEVEL_GOOD);
	}
	
}

?>