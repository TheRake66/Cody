<?php
namespace Kernel\IO;

use Kernel\Debug\Log;
use Kernel\IO\Stream;
use Kernel\URL\Parser;

/**
 * Librairie gerant les demandes d'API REST
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Rest {

    /**
	 * @var array Liste des liens API [ string => class ]
     */
	private static $links = [];

    /**
	 * @var string Lien actuel
     */
	private static $current;


	/**
	 * Charge les liens
	 * 
	 * @return void
	 * @throws Error si le fichier de lien n'est pas trouvé
	 */
	static function load() {
		Path::require('.kernel/api.php', true);
	}


	/**
	 * Ajoute un lien
	 * 
	 * @param string $link Lien
	 * @param string $class Classe
	 * @return void
	 */
	static function add($link, $class) {
		self::$links[$link] = $class;
	}
	

	/**
	 * Verifie si le lien existe
	 * 
	 * @param string le lien
	 * @return bool si le lien existe
	 */
	static function exists($link) {
		return isset(self::$links[$link]);
	}







	/**
	 * Verifi si une requete REST a ete envoyer en GET
	 * 
	 * @param string nom de la fonction demandee
	 * @param function fonction anonyme a executee en cas de demande
	 * @return void
	 */
	static function get($name, $fn) {
		self::run($_GET, 'get', $name, $fn);
	}


	/**
	 * Verifi si une requete REST a ete envoyer en POST
	 * 
	 * @param string nom de la fonction demandee
	 * @param function fonction anonyme a executee en cas de demande
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
	 * @param function fonction anonyme a executee en cas de demande
	 * @return void
	 */
	private static function run($array, $method, $name, $fn) {
        if (isset($array['_']) && $array['_'] === $name) {
			unset($array['_']);
			Log::add('Exécution de la requête REST (méthode : "' . $method . '", composant : "' . debug_backtrace()[2]['class'] . '", fonction : "' .  $name . '", url : "' . Parser::getCurrent() . '")...', Log::LEVEL_PROGRESS, Log::TYPE_QUERY);
			Log::add('Paramètres de la requête REST : "' . print_r($array, true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_PARAMETERS);
			$res = $fn();
			Log::add('Requête REST exécutée.', Log::LEVEL_GOOD, Log::TYPE_QUERY);
			Log::add('Résultat de la requête REST : "' . print_r(json_encode($res, JSON_PRETTY_PRINT), true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_RESULTS);
			Stream::destroy();
			Stream::start();
			echo json_encode($res);
			Stream::close();
            exit;
        }
	}
	
}
