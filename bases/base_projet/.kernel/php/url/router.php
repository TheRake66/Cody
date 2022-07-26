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
	 * @var int L'index de la route actuelle
     */
	private static $index = 0;


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
	 * Ajoute une route.
	 * 
	 * @param string $route La route.
	 * @param array|string $class La ou les classes de la route.
	 * @param array|string $method La ou les méthodes de la route.
	 * @return void
	 */
	static function add($route, $class, $method = self::METHOD_GET) {
		if (!self::exists($route)) {
			if (!is_array($class)) {
				$class = [ $class ];
			}
			if (!is_array($method)) {
				$method = [ $method ];
			}
			self::$routes[$route] = [ $class, $method ];
		} else {
			Error::trigger('La route "' . $route . '" existe déjà !');
		}
	}


	/**
	 * Copie les détails d'une route vers une ou plusieurs autres routes.
	 * 
	 * @param string $source La route à copier.
	 * @param array|string $destination La ou les routes à créer.
	 * @return void
	 * @throws Error Si la route source n'existe pas. Ou qu'une des routes de destination existe déjà.
	 */
	static function copy($source, $destination) {
		if (self::exists($source)) {
			if (!is_array($destination)) {
				$destination = [ $destination ];
			}
			foreach ($destination as $dest) {
				if (!self::exists($dest)) {
					self::$routes[$dest] = self::$routes[$source];
				} else {
					Error::trigger('La route "' . $dest . '" existe déjà ! Impossible de la copier sans l\'écraser !');
				}
			}
		} else {
			Error::trigger('La route "' . $source . '" n\'existe pas ! Impossible de la copier !');
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
				$route = self::match();
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
	 * Retourne la classe de l'index actuel de la séquence.
	 * 
	 * @return string La classe de l'index actuel de la séquence.
	 */
	static function class() {
		return self::$routes[self::current()][0][self::$index];
	}
	

	/**
	 * Retourne la séquence de classes liée à la route actuelle.
	 * 
	 * @return array La séquence classes liée à la route actuelle.
	 */
	static function sequence() {
		return self::$routes[self::current()][0];
	}
	

	/**
	 * Retourne le point d'entrée de la route actuelle.
	 * 
	 * @return string Le point d'entrée lié à la route actuelle.
	 */
	static function entry() {
		return self::$routes[self::current()][0][0];
	}


	/**
	 * Retourne les méthodes liées à la route actuelle.
	 * 
	 * @return array Les méthodes liées à la route actuelle.
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
	 * Retourne la route correspondant à la route demandée, 
	 * puis en extrait les paramètres de la route pour définir la 
	 * variable $GLOBALS['_ROUTE'].
	 * 
	 * @return string|null La route correspondant à la route passée 
	 * en paramètre, ou null si aucune route ne correspond.
	 */
	private static function match() {
		$asked = self::asked();
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
		$entry = self::entry();
		Log::add('Routage (url : "' . Parser::current() . '")...', Log::LEVEL_PROGRESS);
		if (Autoloader::typeof($entry) === Autoloader::TYPE_CONTROLLER) {
			Log::add('Contrôleur identifié : "' . $entry . '".');
			$sequence = self::sequence();
			$count = count($sequence);
			if ($count > 1) {
				$path = '';
				for ($i=1; $i < $count; $i++) { 
					$path = '"' . $sequence[$i] . '" -> ';
				}
				$path = substr($path, 0, -4);
				Log::add('Enchaînement : ' . $path);
			}
			new $entry();
			Log::add('Routage fait.', Log::LEVEL_GOOD);
		} else {
			Error::trigger('La route "' . self::$current . '" n\'est pas une route de composant !');
		}
	}


    /**
     * Appel le contrôleur du composant suivant.
	 * 
	 * @param bool $return Indique si on doit incrémenter l'index de la séquence
	 * et instancier le composant suivant. Ou si on doit uniquement la class du composant suivant.
	 * @return void
     */
	static function next($return = false) {
		$sequence = self::sequence();
		$index = self::$index;
		if ($index < count($sequence) - 1) {
			$index++;
		}
		$class = $sequence[$index];
		if ($return) {
			return $class;
		} else {
			self::$index = $index;
			Log::add('Enchaînement suivant vers : "' . $class . '"...', Log::LEVEL_PROGRESS);
			new $class();
			Log::add('Enchaînement fait.', Log::LEVEL_GOOD);
		}
	}


    /**
     * Appel le contrôleur du composant précédent.
	 * 
	 * @param bool $return Indique si on doit incrémenter l'index de la séquence
	 * et instancier le composant précédent. Ou si on doit uniquement la class du composant précédent.
	 * @return void
     */
	static function previous($return = false) {
		$sequence = self::sequence();
		$index = self::$index;
		if ($index > 0) {
			$index--;
		}
		$class = $sequence[$index];
		if ($return) {
			return $class;
		} else {
			self::$index = $index;
			Log::add('Enchaînement précédent vers : "' . $class . '"...', Log::LEVEL_PROGRESS);
			new $class();
			Log::add('Enchaînement fait.', Log::LEVEL_GOOD);
		}
	}

}

?>