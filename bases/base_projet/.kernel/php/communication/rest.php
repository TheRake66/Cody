<?php

namespace Kernel\Communication;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\Io\Autoloader;
use Kernel\Io\Convert\Encoded;
use Kernel\Io\Stream;
use Kernel\Security\Configuration;
use Kernel\Security\Vulnerability\Xss;
use Kernel\Session\Socket;
use Kernel\Url\Parser;
use Kernel\Url\Router;


/**
 * Librairie gérant les appels d'API REST.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.1.1.0
 * @since Cody 7(21.65.0)
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 * @license MIT License
 * @package Kernel\Communication
 * @category Framework source
 */
abstract class Rest {

    /**
	 * @access private
     * @var int Temps UNIX en MS a l'exécution de la requête.
	 */
    private $started;


	/**
	 * Exécute la fonction API de la route demandée.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	static function check() {
		$class = Router::entry();
		Log::progress('Vérification de l\'appel API...');
		if (Autoloader::typeof($class) === Autoloader::TYPE_API) {
			Log::good("Appel API identifié : \"$class\".");
			Log::progress('Traitement de l\'appel API...');

			if (Socket::exist()) {
				Socket::close();
			}

			$object = new $class();
			$object->started = microtime(true);
			$method = $_SERVER['REQUEST_METHOD'];
			$methods = Router::methods();
			if ((is_array($methods) && (in_array($method, $methods) || in_array(Router::METHOD_ALL, $methods))) || 
				(!is_array($methods) && ($methods === $method || $methods === Router::METHOD_ALL))) {
				$route = (object)Router::params();
				$query = (object)$_GET;
				$body = (object)json_decode(file_get_contents('php://input'));

				Log::progress('Exécution de la requête REST (méthode : "' . $method . '", url : "' . Parser::current() . '")...', Log::TYPE_API);
				Log::info('Paramètres de la requête REST (route) : "' . print_r($route, true) . '".', Log::TYPE_API_PARAMETERS);
				Log::info('Paramètres de la requête REST (query) : "' . print_r($query, true) . '".', Log::TYPE_API_PARAMETERS);
				Log::info('Paramètres de la requête REST (body) : "' . print_r($body, true) . '".', Log::TYPE_API_PARAMETERS);

				$function = strtolower($method);
				if (method_exists($object, $function)) {
					$object->$function($route, $query, $body);
					$object->send(null, Error::API_NONE_FUNCTION_RETURN, "Aucune réponse de la fonction \"$function\" du module d'API \"$class\" !", Http::HTTP_INTERNAL_SERVER_ERROR);
				} else {
					$object->send(null, Error::API_FUNCTION_NOT_FOUND, "La méthode d\'API \"$function\" n'existe pas dans la ressource !", Http::HTTP_INTERNAL_SERVER_ERROR);
				}
			} else {
				$object->send(null, Error::API_HTTP_METHOD_NOT_ALLOWED, 'La méthode "' . $method . '" n\'est pas supportée par cette ressource !', Http::HTTP_METHOD_NOT_ALLOWED);
			}
		} else {
			Log::good('Aucun appel API.');
		}
	}


	/**
	 * Formate et envoie la réponse au client.
	 * 
	 * @access protected
	 * @param mixed $content [optional] [default = null] Le contenu à envoyer.
	 * @param int $code [optional] [default = 0] Le code de retour.
	 * @param string $message [optional] [default = ''] Le message de retour.
	 * @param int $status [optional] [default = 200] Le statut HTTP.
	 * @return void
	 */
	protected function send(
		$content = null,
		$code = 0, 
		$message = '', 
		$status = Http::HTTP_OK
	) {
		$ended = microtime(true);
		$time = round(($ended - $this->started) * 1000);
		$response = (object)[
			'status' => $status,
			'message' => $message,
			'code' => $code,
			'time' => $time,
			'content' => $content
		];

		Log::good('Requête REST exécutée.', Log::TYPE_API);
		Log::info('Résultat de la requête REST : "' . print_r($response, true) . '".', Log::TYPE_API_RESPONSE);
		
		$beauty = Configuration::get()->render->api->beautify_json;
		$flags = (!$beauty ? 0 : JSON_PRETTY_PRINT) | JSON_PARTIAL_OUTPUT_ON_ERROR;

		Stream::reset();
		http_response_code($status);
		header('Content-Type: application/json; charset=utf-8');
		echo(json_encode($response, $flags));
		Stream::close();

		exit();
	}


	/**
	 * Vérifie si une route correspond à la route demandée, si oui, on exécute la fonction correspondante.
	 * 
	 * @access protected
	 * @param string $route La route à vérifier.
	 * @param callable $callback La fonction à exécuter.
	 * @return void
	 */
	protected function match(
		$route, 
		$callback
	) {
		if (Router::current() === $route) {
			$callback();
		}
	}


	/**
	 * Retourne un paramètre d'un objet, s'il n'est pas trouvé, 
	 * on renvoie une erreur.
	 * 
	 * @access protected
	 * @param object $object L'objet de paramètres.
	 * @param string $name Le nom du paramètre.
	 * @param bool $needed [optional] [default = true] Si le paramètre est obligatoire.
	 * @param bool $convert [optional] [default = true] Si on doit convertir une valeur vide en NULL.
	 * @param bool $trim [optional] [default = true] Si on doit supprimer les espaces en début et fin de la valeur.
	 * @param bool $sanitize [optional] [default = true] Si on doit filtrer la valeur contre la vulnérabilité XSS.
	 * @return ?mixed La valeur du paramètre.
	 */
	protected function data(
		$object, 
		$name, 
		$needed = true,
		$convert = true,
		$trim = true,
		$sanitize = true
	) {
		if (property_exists($object, $name)) {
			$value = $object->$name;
			if (is_string($value)) {
				if ($sanitize) $value = Xss::sanitize($value);
				if ($trim) $value = trim($value);
				if ($convert) $value = Encoded::null($value);
			}
			return $value;
		}
		if ($needed) {
			$this->send(null, Error::API_MISSING_PARAMETER, 'Le paramètre "' . $name . '" n\'est pas défini !', Http::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * Génère un composant pour l'envoyer.
	 * 
	 * @access protected
	 * @param string $class La classe du composant.
	 * @param mixed $args [optional] [default = null] La ou les paramètres du composant.
	 * @return string Le composant généré.
     * @throws Error Si la classe demandée n'est pas un composant.
	 */
	protected function generate($class, $args = null) {
		if (Autoloader::typeof($class) === Autoloader::TYPE_CONTROLLER) {
			return Stream::toogle(function() use($class, $args) {
				if (is_null($args)) {
					new $class();
				} else if (is_array($args)) {
					(new \ReflectionClass($class))
						->newInstanceArgs($args);
				} else {
					new $class($args);
				}
			});
		} else {
			Error::trigger("La classe \"$class\" n'est pas un composant !");
		}
	}
	
}

?>