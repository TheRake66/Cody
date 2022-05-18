<?php
namespace Kernel\Communication;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\IO\Autoloader;
use Kernel\IO\Stream;
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
     * @var string les methodes d'envoie
	 */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
	const METHOD_PATCH = 'PATCH';

    /**
     * @var int Temps UNIX en MS a l'execution de la requete
	 */
    private static $started;


	/**
	 * Appel la fonction API de la route demandée
	 * 
	 * @return void
	 */
	static function resting() {
		$class = Router::getClass();

		Log::add('Vérification de l\'appel API...', Log::LEVEL_PROGRESS);

		if (Autoloader::getType($class) === 'API') {
			
			Log::add('Appel API identifié : "' . $class . '".');
			Log::add('Traitement de l\'appel API...', Log::LEVEL_PROGRESS);

			$method = $_SERVER['REQUEST_METHOD'];
			$route = Router::getParams();
			$query = [];
			$function = strtolower($method);
			switch ($method) {
				case self::METHOD_GET:
					$query = $_GET;
					break;
				case self::METHOD_POST:
					$query = $_POST;
					break;
				case self::METHOD_PUT:
				case self::METHOD_DELETE:
				case self::METHOD_PATCH:
					parse_str(file_get_contents("php://input"), $query);
					break;
				default:
					Error::trigger('La méthode "' . $method . '" n\'est pas supportée.');
					break;
			}	

			Log::add('Exécution de la requête REST (méthode : "' . $method . '", url : "' . Parser::getCurrent() . '")...',
				Log::LEVEL_PROGRESS, Log::TYPE_QUERY);
			Log::add('Paramètres de la requête REST : "' . print_r($query, true) . '".',
				Log::LEVEL_INFO, Log::TYPE_QUERY_PARAMETERS);

			$object = new $class();
			if (method_exists($object, $function)) {
				$object->$function($route, $query);
			} else {
				Error::trigger('La méthode d\'API "' . $function . '" n\'existe pas dans la classe "' . $class . '" !');
			}

		} else {
			Log::add('Aucun appel API.', Log::LEVEL_GOOD);
		}
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
	protected function sendResponse($content = null, $code = 0, $message = '', $status = 200) {
		$ended = microtime(true);
		$time = round(($ended - self::$started) * 1000);
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
		
		Stream::reset();
		echo json_encode($response);
		Stream::close();

		exit();
	}
	
}

?>