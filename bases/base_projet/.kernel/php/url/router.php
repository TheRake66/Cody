<?php
namespace Kernel\URL;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\IO\Autoloader;
use Kernel\IO\Path;
use Kernel\URL\Parser;



/**
 * Librairie gerant le routage des pages
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\URL
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Router {

	/**
     * @var string les methodes d'envoie
	 */
    const METHOD_ALL = 'ALL';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
	const METHOD_PATCH = 'PATCH';

    /**
	 * @var array Liste des routes [ route => [ class, methods ] ]
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
	 * @param array la ou les methodes a executer
	 * @return void
     */
	static function add($route, $class, $methods = self::METHOD_GET) {
		self::$routes[$route] = [ $class, $methods ];
	}


	/**
	 * Ajoute plusieurs routes
	 * 
	 * @param array liste des routes
	 * @return void
	 */
	static function addMany($routes) {
		foreach ($routes as $route => $array) {
			if (is_array($array)) {
				self::add($route, $array[0], $array[1]);
			} else {
				self::add($route, $array);
			}
		}
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
	 * Retourne la route actuelle tel qu'elle est definie dans le fichier de route
	 * 
	 * @return string la route
	 * @throws Error si aucune route n'a ete definie
	 */
	static function current() {
		if (is_null(self::$current)) {
			$route = null;
			$asked = self::getAsked();
			if ($asked !== '/') {
				$route = self::whoMatch($asked);
				if (is_null($route)) {
					if (self::exists(self::$notfound)) {
						http_response_code(404);
						$route = self::$notfound;
					} elseif (self::exists(self::$default)) {
						$route = self::$default;
					} else {
						$route = self::getFirst();
					}
				}
			} elseif (self::exists(self::$default)) {
				$route = self::$default;
			} else {
				$route = self::getFirst();
			}
			if (is_null($route)) {
				Error::trigger('Aucune route n\'a été définie !');
			}
			self::$current = $route;
			return $route;
		} else {
			return self::$current;
		}
	}


	/**
	 * Retourne la route actuelle tel qu'elle est definie dans l'URL
	 * 
	 * @return string le route demandee
	 */
	static function getAsked() {
		if (isset($_SERVER['PATH_INFO'])) {
			return $_SERVER['PATH_INFO'];
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
		return self::$routes[self::current()][0];
	}


	/**
	 * Retourne la ou les methodes liees a la route actuelle
	 * 
	 * @return array la ou les methodes
	 */
	static function getMethods() {
		return self::$routes[self::current()][1];
	}

	
	/**
	 * Retourne les parametres de la route actuelle
	 * 
	 * @return array les parametres
	 */
	static function getParams() {
		return $GLOBALS['_ROUTE'] ?? [];
	}


	/**
	 * Retourne la premiere route
	 * 
	 * @return string la premiere route
	 */
	private static function getFirst() {
		if (count(self::$routes) > 0) {
			return array_keys(self::$routes)[0];
		}
	}


	/**
	 * Retourne la route correspondante a une route passée en parametre,
	 * puis en extrait les parametres de la route pour definir la
	 * variable $GLOBALS['_ROUTE']
	 * 
	 * @param string l'url
	 * @return string la route ou null si aucune correspondance
	 */
	private static function whoMatch($asked) {
		if (!is_null($asked)) {
			$split_asked = explode('/', $asked);
			foreach (self::$routes as $route => $array) {
				$split_route = explode('/', $route);
				if (count($split_route) == count($split_asked)) {
					$i = 0;
					$match = true;
					$params = [];
					while ($i < count($split_asked) && $i < count($split_route) && $match) {
						$word_asked = $split_asked[$i];
						$word_route = $split_route[$i];
						if (!empty($word_asked) && !empty($word_route)) {
							if (substr($word_route, 0, 1) === '{' &&
								substr($word_route, -1) === '}') {
								$params[substr($word_route, 1, -1)] = $word_asked;
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
     * Appel le controleur du composant de la route demandée
	 * 
	 * @return void
     */
	static function app() {
		$class = self::getClass();
		
		if (Autoloader::type($class) === 'Controller') {

			Log::add('Routage (url : "' . Parser::current() . '")...', Log::LEVEL_PROGRESS);
			Log::add('Contrôleur identifié : "' . $class . '".');

			new $class();

			Log::add('Routage fait.', Log::LEVEL_GOOD);

		} else {
			Error::trigger('La route "' . self::current() . '" n\'est pas une route de composant !');
		}
	}
	
}

?>