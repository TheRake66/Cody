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

			Log::add('Exécution de la requête REST (méthode : "' . $method . '", fonction : "' .  $function . '", url : "' . Query::remove('rest_function') . '")...', Log::LEVEL_PROGRESS, Log::TYPE_QUERY);
			Log::add('Paramètres de la requête REST : "' . print_r($array, true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_PARAMETERS);

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
				$started = microtime(true);
				$res = $reflect
					->getMethod($function)
					->invoke(new $class());
				$ended = microtime(true);
				$res['time'] = round(($ended - $started) * 1000);

				Log::add('Requête REST exécutée.', Log::LEVEL_GOOD, Log::TYPE_QUERY);
				Log::add('Résultat de la requête REST : "' . print_r(json_encode($res, JSON_PRETTY_PRINT), true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_RESULTS);
				
				Stream::reset();
				echo json_encode((object)$res);
				Stream::close();

				exit();
			} else {
				Error::trigger('La fonction d\'API "' . $function . '" n\'existe pas !');
			}

		} else {
			Log::add('Aucun appel API.', Log::LEVEL_GOOD);
		}
	}


	/**
	 * Formatte la reponse a envoyer au client
	 * 
	 * @param any le contenu de la reponse
	 * @param int le code de retour
	 * @param string le message de retour
	 * @param int le code de l'entete HTTP
	 * @return array la reponse
	 */
	protected function returnJson($content, $code = 0, $message = '', $status = 200) {
		http_response_code($status);
		return [
			'status' => $status,
			'message' => $message,
			'code' => $code,
			'time' => 0,
			'content' => $content
		];
	}
	
}

?>