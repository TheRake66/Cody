<?php
namespace Kernel\Url;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\Io\Autoloader;
use Kernel\Io\File;
use Kernel\Url\Parser;



/**
 * Librairie gerant le routage des pages
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Url
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
	 * @var string Route demandee
     */
	private static $asked;


	/**
	 * Charge les routes
	 * 
	 * @return void
	 * @throws Error si le fichier de route n'est pas trouvé
	 */
	static function load() {
		File::require('.kernel/route.php', true);
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
	 * Ajoute des routes
	 * 
	 * @param array liste des routes
	 * @return void
	 */
	static function add($routes) {
		foreach ($routes as $route => $array) {
			if (is_array($array)) {
				self::$routes[$route] = [ $array[0], $array[1] ];
			} else {
				self::$routes[$route] = [ $array, self::METHOD_GET ];
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
			$asked = self::asked();
			if (!is_null($asked) && $asked !== '/') {
				$route = self::match($asked);
				if (is_null($route)) {
					if (self::exists(self::$notfound)) {
						http_response_code(404);
						$route = self::$notfound;
					} elseif (self::exists(self::$default)) {
						$route = self::$default;
					} else {
						$route = self::first();
					}
				}
			} elseif (self::exists(self::$default)) {
				$route = self::$default;
			} else {
				$route = self::first();
			}
			if (is_null($route)) {
				Error::trigger('Aucune route n\'a été définie !');
			}
			self::$current = $route;
		}
		return self::$current;
	}


	/**
	 * Retourne la route actuelle tel qu'elle est definie dans l'URL
	 * 
	 * @return string le route demandee
	 */
	static function asked() {
		if (is_null(self::$asked)) {
			if (isset($_SERVER['PATH_INFO'])) {
				self::$asked = $_SERVER['PATH_INFO'];
			} elseif (isset($_SERVER['REDIRECT_URL'])) {
				self::$asked = substr($_SERVER['REDIRECT_URL'], strlen($_SERVER['REDIRECT_BASE']));
			} else {
				self::$asked = '/';
			}
		}
		return self::$asked;
	}
	

	/**
	 * Retourne la classe liee a la route actuelle
	 * 
	 * @return object la classe
	 */
	static function class() {
		return self::$routes[self::current()][0];
	}


	/**
	 * Retourne la ou les methodes liees a la route actuelle
	 * 
	 * @return array la ou les methodes
	 */
	static function methods() {
		return self::$routes[self::current()][1];
	}

	
	/**
	 * Retourne les parametres de la route actuelle
	 * 
	 * @return array les parametres
	 */
	static function params() {
		return $GLOBALS['_ROUTE'] ?? [];
	}


	/**
	 * Retourne la premiere route
	 * 
	 * @return string la premiere route
	 */
	private static function first() {
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
	private static function match($asked) {
		$split_asked = explode('/', $asked);
		$count_asked = count($split_asked);
		foreach (self::$routes as $route => $array) {
			$split_route = explode('/', $route);
			$count_route = count($split_route);
			if ($count_route == $count_asked) {
				$i = 0;
				$match = true;
				$params = [];
				while ($i < $count_asked && $i < $count_route && $match) {
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


    /**
     * Appel le controleur du composant de la route demandée
	 * 
	 * @return void
     */
	static function app() {
		$class = self::class();
		Log::add('Routage (url : "' . Parser::current() . '")...', Log::LEVEL_PROGRESS);
		if (Autoloader::typeof($class) === 'Controller') {
			Log::add('Contrôleur identifié : "' . $class . '".');
			new $class();
			Log::add('Routage fait.', Log::LEVEL_GOOD);
		} else {
			Error::trigger('La route "' . self::$current . '" n\'est pas une route de composant !');
		}
	}
	
}

?>