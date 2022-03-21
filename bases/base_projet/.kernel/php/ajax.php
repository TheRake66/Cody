<?php
namespace Kernel;



// Librairie Ajax
class Ajax {

	/**
	 * Verifi si une demande Ajax a ete envoyer dans le GET
	 * 
	 * @param string nom de la demande
	 * @param object fonction anonyme a executee en cas de demande
	 */
	static function askGET($name, $fn) {
		self::ajaxRun($_GET, 'get', $name, $fn);
	}


	/**
	 * Verifi si une demande Ajax a ete envoyer dans le POST
	 * 
	 * @param string nom de la demande
	 * @param object fonction anonyme a executee en cas de demande
	 */
	static function askPOST($name, $fn) {
		self::ajaxRun($_POST, 'post', $name, $fn);
	}


	/**
	 * Verifi, lance et log une fonction AJAX
	 *
	 * @param array la liste des variable a verifier
	 * @param string le nom de la methode
	 * @param string nom de la demande
	 * @param object fonction anonyme a executee en cas de demande
	 */
	private static function ajaxRun($array, $method, $name, $fn) {
        if (isset($array[$name])) {
			Debug::log('Exécution de la requête AJAX (méthode : "' . $method . '", composant : "' . debug_backtrace()[2]['class'] . '", fonction : "' .  $name . '", url : "' . $_SERVER['REQUEST_URI'] . '")...', Debug::LEVEL_PROGRESS, Debug::TYPE_QUERY);
			Debug::log('Paramètres de la requête AJAX : "' . print_r($array, true) . '".', Debug::LEVEL_INFO, Debug::TYPE_QUERY_PARAMETERS);
			$res = $fn();
			Debug::log('Requête AJAX exécutée.', Debug::LEVEL_GOOD, Debug::TYPE_QUERY);
			Debug::log('Résultat de la requête AJAX : "' . print_r(json_encode($res, JSON_PRETTY_PRINT), true) . '".', Debug::LEVEL_INFO, Debug::TYPE_QUERY_RESULTS);
			Debug::separator();
			ob_end_clean();
			echo json_encode($res);
            exit;
        }
	}
	
}
