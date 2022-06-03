<?php
namespace Kernel\Communication;

use Kernel\Debug\Log;
use Kernel\IO\Autoloader;
use Kernel\IO\Convert\Encoded;
use Kernel\IO\Stream;
use Kernel\Security\Configuration;
use Kernel\URL\Parser;
use Kernel\URL\Router;


/**
 * Librairie gerant les appel d'API REST
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Communication
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Rest {

    /**
     * @var int Temps UNIX en MS a l'execution de la requete
	 */
    private $started;


	/**
	 * Appel la fonction API de la route demandée
	 * 
	 * @return void
	 */
	static function check() {
		$class = Router::class();
		Log::add('Vérification de l\'appel API...', Log::LEVEL_PROGRESS);
		if (Autoloader::typeof($class) === 'API') {
			Log::add('Appel API identifié : "' . $class . '".');
			Log::add('Traitement de l\'appel API...', Log::LEVEL_PROGRESS);

			$object = new $class();
			$object->started = microtime(true);
			$method = $_SERVER['REQUEST_METHOD'];
			$methods = Router::methods();
			if (is_array($methods) && in_array($method, $methods) ||
				!is_array($methods) && ($methods === $method || $methods === Router::METHOD_ALL)) {
				$route = Router::current();
				$query = Router::params();
				$body = [];
				switch ($method) {
					case Router::METHOD_GET:
						$body = $_GET;
						break;
					case Router::METHOD_POST:
						$body = $_POST;
						break;
					case Router::METHOD_PUT:
					case Router::METHOD_DELETE:
					case Router::METHOD_PATCH:
						$body = self::extract();
						break;
					default:
						$object->send(null, 1, 'La méthode "' . $method . '" n\'est pas supportée par le serveur !', 405);
						break;
				}	

				Log::add('Exécution de la requête REST (méthode : "' . $method . '", url : "' . Parser::current() . '")...',
					Log::LEVEL_PROGRESS, Log::TYPE_QUERY);
				Log::add('Paramètres de la requête REST : "' . print_r($body, true) . '".',
					Log::LEVEL_INFO, Log::TYPE_QUERY_PARAMETERS);

				$function = strtolower($method);
				if (method_exists($object, $function)) {
					$object->$function($route, $query, $body);
					$object->send();
				} else {
					$object->send(null,1, 'La méthode d\'API "' . $function . '" n\'existe pas dans la ressource !', 500);
				}
			} else {
				$object->send(null, 1, 'La méthode "' . $method . '" n\'est pas supportée par cette ressource !', 405);
			}
		} else {
			Log::add('Aucun appel API.', Log::LEVEL_GOOD);
		}
	}


	/**
	 * Extrait le contenu du corps de la requete
	 * 
	 * @return array le contenu du corps de la requete
	 */
	private static function extract() {
		$input = file_get_contents('php://input');
		$lines = explode(PHP_EOL, $input);
		$boundary = $lines[0];
		$data = [];
		$name = '';
		for ($i = 0; $i < count($lines) - 2; $i++) {
			$line = $lines[$i];
			if ($line === $boundary) {
				$i++;
				$line = $lines[$i];
				$posname = strpos($line, 'name="');
				$posfile = strpos($line, '"; filename="');
				$offset = $posname + 6;
				$lenght = $posfile ? $posfile - $posname - 6 : -1;
				$name = substr($line, $offset, $lenght);
				$data[$name] = null;
				while (!empty($line)) {
					$i++;
					$line = $lines[$i];
				}
			} else {
				$data[$name] .= $line;
			}
		}
		return $data;
	}


	/**
	 * Formatte et envoi la reponse a envoyer au client
	 * 
	 * @param any le contenu de la reponse
	 * @param int le code de retour
	 * @param string le message de retour
	 * @param int le code de l'entete HTTP
	 * @return void
	 */
	protected function send($content = null, $code = 0, $message = '', $status = 200) {
		$ended = microtime(true);
		$time = round(($ended - $this->started) * 1000);
		$response = (object)[
			'status' => $status,
			'message' => $message,
			'code' => $code,
			'time' => $time,
			'content' => $content
		];
		http_response_code($status);

		Log::add('Requête REST exécutée.',
			Log::LEVEL_GOOD, Log::TYPE_QUERY);
		Log::add('Résultat de la requête REST : "' . print_r(json_encode($response, JSON_PRETTY_PRINT), true) . '".',
			Log::LEVEL_INFO, Log::TYPE_QUERY_RESULTS);
		
		$beauty = Configuration::get()->render->api_beautify_json;
		Stream::reset();
		echo json_encode($response, !$beauty ? 0 : JSON_PRETTY_PRINT);
		Stream::close();

		exit();
	}


	/**
	 * Verifi si une route correspond a la route demandée, si oui, 
	 * on execute la fonction correspondante
	 * 
	 * @param string la route demandée
	 * @param function la fonction a executer
	 * @return void
	 */
	protected function match($route, $callback) {
		if (Router::current() === $route) {
			$callback();
		}
	}


	/**
	 * Retourne un parametre du tableau, si il n'est pas trouvé,
	 * on renvoi une erreur
	 * 
	 * @param array le tableau de parametres
	 * @param string le nom du parametre
	 * @param bool si vide doit retourner null
	 * @return any la valeur du parametre
	 */
	protected function data($array, $name, $convert = false) {
		if (isset($array[$name])) {
			return $convert ? 
				Encoded::null($array[$name]) : 
				$array[$name];
		} else {
			$this->send(null, 1, 'Le paramètre "' . $name . '" n\'est pas défini !', 400);
		}
	}
	
}

?>