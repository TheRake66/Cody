<?php
namespace Kernel\Communication;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\IO\Autoloader;
use Kernel\IO\Stream;
use Kernel\URL\Query;
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
			} elseif (
				isset($GLOBALS['_ROUTE']) &&
				isset($GLOBALS['_ROUTE']['rest_function'])) {
				$array = $GLOBALS['_ROUTE'];
				$function = $GLOBALS['_ROUTE']['rest_function'];
				$method = 'ROUTE';
			} else {
				Error::trigger('Aucune fonction API n\'a été spécifiée !');
			}		

			Log::add('Exécution de la requête REST (méthode : "' . $method . '", fonction : "' .  $function . '", url : "' . Query::remove('rest_function') . '")...',
				Log::LEVEL_PROGRESS, Log::TYPE_QUERY);
			Log::add('Paramètres de la requête REST : "' . print_r($array, true) . '".',
				Log::LEVEL_INFO, Log::TYPE_QUERY_PARAMETERS);

			$reflect = new \ReflectionClass($class);
			$methods = $reflect->getMethods();
			
			$found = false;
			foreach ($methods as $method) {
				if ($method->name == $function) {
					$found = true;
					break;
				}
			}

			if ($found) {
				self::$started = microtime(true);
				$reflect
					->getMethod($function)
					->invoke(new $class());
			} else {
				Error::trigger('La fonction d\'API "' . $function . '" n\'existe pas !');
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