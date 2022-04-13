<?php
namespace Kernel;



/**
 * Librairie gerant les demandes d'API REST
 */
class Rest {

	/**
	 * Verifi si une requete REST a ete envoyer en GET
	 * 
	 * @param string nom de la fonction demandee
	 * @param object fonction anonyme a executee en cas de demande
	 * @return void
	 */
	static function get($name, $fn) {
		self::run($_GET, 'get', $name, $fn);
	}


	/**
	 * Verifi si une requete REST a ete envoyer en POST
	 * 
	 * @param string nom de la fonction demandee
	 * @param object fonction anonyme a executee en cas de demande
	 * @return void
	 */
	static function post($name, $fn) {
		self::run($_POST, 'post', $name, $fn);
	}


	/**
	 * Verifi, lance et log une fonction REST
	 *
	 * @param array la liste des variable a verifier
	 * @param string le nom de la methode
	 * @param string nom de la fonction demandee
	 * @param object fonction anonyme a executee en cas de demande
	 * @return void
	 */
	private static function run($array, $method, $name, $fn) {
        if (isset($array['restFunction']) && $array['restFunction'] == $name) {
			Debug::log('Exécution de la requête REST (méthode : "' . $method . '", composant : "' . debug_backtrace()[2]['class'] . '", fonction : "' .  $name . '", url : "' . Url::current() . '")...', Debug::LEVEL_PROGRESS, Debug::TYPE_QUERY);
			Debug::log('Paramètres de la requête REST : "' . print_r($array, true) . '".', Debug::LEVEL_INFO, Debug::TYPE_QUERY_PARAMETERS);
			$res = $fn();
			Debug::log('Requête REST exécutée.', Debug::LEVEL_GOOD, Debug::TYPE_QUERY);
			Debug::log('Résultat de la requête REST : "' . print_r(json_encode($res, JSON_PRETTY_PRINT), true) . '".', Debug::LEVEL_INFO, Debug::TYPE_QUERY_RESULTS);
			Stream::destroy();
			Stream::start();
			echo json_encode($res);
			Stream::close();
            exit;
        }
	}
	
}
