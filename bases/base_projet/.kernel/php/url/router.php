<?php
namespace Kernel\Url;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\Io\Autoloader;
use Kernel\Io\File;
use Kernel\Url\Parser;



/**
 * Librairie gérant le routage des pages.
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
     * @var string Les méthodes d'envoie.
	 */
    const METHOD_ALL = 'ALL';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
	const METHOD_PATCH = 'PATCH';


    /**
	 * @var array Liste des routes [ route => [ class, methods ] ].
     */
	private static $routes = [];


    /**
	 * @var string Route par défaut.
     */
	private static $default;


    /**
	 * @var string Route si non trouvée.
     */
	private static $notfound;


    /**
	 * @var string Route actuelle.
     */
	private static $current;

	
    /**
	 * @var string Route demandée.
     */
	private static $asked;


	/**
	 * Charge les routes.
	 * 
	 * @return void
	 * @throws Error Si le fichier de routes n'existe pas.
	 */
	static function load() {
		File::require('.kernel/route.php', true);
	}


    /**
     * Définit la route par défaut.
	 * 
	 * @param string $defaut La route par défaut.
	 * @return void
     */
	static function default($defaut) {
		self::$default = $defaut;
	}


    /**
     * Définit la route en cas de route non trouvee (404).
	 * 
	 * @param string $notfound La route 404.
	 * @return void
     */
	static function notfound($notfound) {
		self::$notfound = $notfound;
	}


	/**
	 * Ajoute des routes.
	 * 
	 * @example Kernel\Url\Router::add('/accueil' => Controller\Accueil::class);
	 * @example Kernel\Url\Router::add('/api/produit' => Api\Produit::class);
	 * @example Kernel\Url\Router::add('/api/produit' => [ Api\Produit::class, Router::METHOD_GET ]);
	 * @example Kernel\Url\Router::add('/api/produit' => [ Api\Produit::class, [ Router::METHOD_GET, Router::METHOD_POST ] ]);
	 * @param array $routes Liste des routes.
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
	 * Vérifie si une route existe.
	 * 
	 * @param string $route La route à vérifier.
	 * @return bool True si la route existe, false sinon.
	 */
	static function exists($route) {
		return isset(self::$routes[$route]);
	}
	

	/**
	 * Retourne la route actuelle telle qu'elle est définie dans le fichier de route.
	 * 
	 * @return string La route actuelle.
	 * @throws Error Si aucune route n'est définie dans le fichier de route.
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
	 * Retourne la route actuelle telle qu'elle est définie dans l'URL.
	 * 
	 * @return string La route actuelle.
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
	 * Retourne la classe liée à la route actuelle.
	 * 
	 * @return object La classe liée à la route actuelle.
	 */
	static function class() {
		return self::$routes[self::current()][0];
	}


	/**
	 * Retourne-la ou les méthodes liées à la route actuelle.
	 * 
	 * @return array La ou les méthodes liées à la route actuelle.
	 */
	static function methods() {
		return self::$routes[self::current()][1];
	}

	
	/**
	 * Retourne les paramètres de la route actuelle.
	 * 
	 * @return array Les paramètres de la route actuelle.
	 */
	static function params() {
		return $GLOBALS['_ROUTE'] ?? [];
	}


	/**
	 * Retourne la première route.
	 * 
	 * @return string La première route.
	 */
	private static function first() {
		if (count(self::$routes) > 0) {
			return array_keys(self::$routes)[0];
		}
	}


	/**
	 * Retourne la route correspondant à une route passée en paramètre, puis en extrait les paramètres de la route pour définir la variable $GLOBALS['_ROUTE'].
	 * 
	 * @param string $route La route à vérifier.
	 * @return string|null La route correspondant à la route passée en paramètre, ou null si aucune route ne correspond.
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
					if ($word_asked !== '' && $word_route !== '') {
						if (substr($word_route, 0, 1) === '{' &&
							substr($word_route, -1) === '}') {
							$params[substr($word_route, 1, -1)] = $word_asked;
						} elseif ($word_route !== $word_asked) {
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
     * Appel le contrôleur du composant de la route demandée.
	 * 
	 * @return void
     */
	static function app() {
		$class = self::class();
		Log::add('Routage (url : "' . Parser::current() . '")...', Log::LEVEL_PROGRESS);
		if (Autoloader::typeof($class) === Autoloader::TYPE_CONTROLLER) {
			Log::add('Contrôleur identifié : "' . $class . '".');
			new $class();
			Log::add('Routage fait.', Log::LEVEL_GOOD);
		} else {
			Error::trigger('La route "' . self::$current . '" n\'est pas une route de composant !');
		}
	}
	
}

?>