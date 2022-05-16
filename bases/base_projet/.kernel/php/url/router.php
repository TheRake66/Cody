<?php
namespace Kernel\URL;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\IO\Autoloader;
use Kernel\IO\Path;
use Kernel\IO\Stream;
use Kernel\URL\Parser;



/**
 * Librairie gerant le routage des pages
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Router {

    /**
	 * @var array Liste des routes [ route => class ]
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
		Path::require('.kernel/route.php', true);
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
	static function add($route, $class) {
		self::$routes[$route] = $class;
	}


	/**
	 * Verifie si une route existe
	 * 
	 * @param string la route
	 * @param int type de route
	 * @return bool true si existe, false sinon
	 */
	static function exists($route) {
		return isset(self::$routes[$route]);
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
	 * Retourne la classe liee a la route actuelle
	 * 
	 * @return object la classe
	 */
	static function getClass() {
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
     * Appel le controleur du composant de la route demandée
	 * 
	 * @return void
     */
	static function routing() {
		$class = self::getClass();
		
		if (Autoloader::getType($class) === 'Controller') {

			Log::add('Routage (url : "' . Parser::getCurrent() . '")...', Log::LEVEL_PROGRESS);
			Log::add('Contrôleur identifié : "' . $class . '".');

			new $class();

			Log::add('Routage fait.', Log::LEVEL_GOOD);

		} else {
			Error::trigger('La route "' . self::getCurrent() . '" n\'est pas une route de composant !');
		}
	}


	/**
	 * Appel la fonction API de la route demandée
	 * 
	 * @return void
	 */
	static function resting() {
		$class = self::getClass();

		Log::add('Vérification de l\'appel API...', Log::LEVEL_PROGRESS);

		if (Autoloader::getType($class) === 'API') {
			
			Log::add('Appel API identifié : "' . $class . '".');
			Log::add('Traitement de l\'appel API...', Log::LEVEL_PROGRESS);

			$array = [];
			$function = null;
			$method = null;
			if (isset($_GET['rest_function'])) {
				$array = $_GET;
				$function = $_GET['rest_function'];
				$method = 'GET';
			} elseif (isset($_POST['rest_function'])) {
				$array = $_POST;
				$function = $_POST['rest_function'];
				$method = 'POST';
			} elseif (isset($_ROUTE['rest_function'])) {
				$array = $_ROUTE;
				$function = $_ROUTE['rest_function'];
				$method = 'ROUTE';
			} else {
				Error::trigger('Aucune fonction API n\'a été spécifiée !');
			}
			unset($array['rest_function']);			

			Log::add('Exécution de la requête REST (méthode : "' . $method . '", fonction : "' .  $function . '", url : "' . Parser::getCurrent() . '")...', Log::LEVEL_PROGRESS, Log::TYPE_QUERY);
			Log::add('Paramètres de la requête REST : "' . print_r($array, true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_PARAMETERS);

			$reflect = new \ReflectionClass($class);
			$methods = $class->getMethods();
			if (in_array($function, $methods)) {
				$res = $reflect
					->getMethod($function)
					->invoke($class);

				Log::add('Requête REST exécutée.', Log::LEVEL_GOOD, Log::TYPE_QUERY);
				Log::add('Résultat de la requête REST : "' . print_r(json_encode($res, JSON_PRETTY_PRINT), true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_RESULTS);
				
				Stream::reset();
				echo json_encode($res);
				Stream::close();

				exit();
			} else {
				Error::trigger('La fonction d\'API "' . $function . '" n\'existe pas !');
			}

		} else {
			Log::add('Aucun appel API.', Log::LEVEL_GOOD);
		}
	}
	
}

?>