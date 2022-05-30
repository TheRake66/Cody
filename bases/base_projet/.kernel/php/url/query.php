<?php
namespace Kernel\URL;



/**
 * Librairie gerant les parametres de l'url
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\URL
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Query {

	/**
	 * Remplace un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @param string sa nouvelle valeur
	 * @return string le nouvel url
	 */
	static function change($name, $value) {
		$query = $_GET;
		$query[$name] = $value;
		return Parser::root() . '?' . http_build_query($query);
	}
	

	/**
	 * Ajoute un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @param string sa valeur
	 * @return string le nouvel url
	 */
	static function add($name, $value) {
		return self::change($name, $value);
	}
	

	/**
	 * Retourne un parametre de l'url
	 * 
	 * @param string nom du parametre
	 * @return string valeur du parametre
	 */
	static function get($name) {
		return $_GET[$name] ?? null;
	}


	/**
	 * Supprime un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @return string le nouvel url
	 */
	static function remove($name) {
		$query = $_GET;
		unset($query[$name]);
		return empty($query) ? 
			Parser::root() :
			Parser::root() . '?' . http_build_query($query);
	}

}

?>