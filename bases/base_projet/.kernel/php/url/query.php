<?php
namespace Kernel\Url;



/**
 * Librairie gérant les paramètres de l'URL.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Url
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Query {

	/**
	 * Remplace un paramètre dans l'URL.
	 * 
	 * @param string $name Le nom du paramètre.
	 * @param string $value La valeur du paramètre.
	 * @return string L'URL avec le paramètre remplacé.
	 */
	static function change($name, $value) {
		$query = $_GET;
		$query[$name] = $value;
		return Parser::root() . '?' . http_build_query($query);
	}
	

	/**
	 * Ajoute un paramètre dans l'URL.
	 * 
	 * @param string $name Le nom du paramètre.
	 * @param string $value La valeur du paramètre.
	 * @return string L'URL avec le paramètre ajouté.
	 */
	static function add($name, $value) {
		return self::change($name, $value);
	}
	

	/**
	 * Retourne un paramètre de l'URL.
	 * 
	 * @param string $name Le nom du paramètre.
	 * @return string La valeur du paramètre.
	 */
	static function get($name) {
		return $_GET[$name] ?? null;
	}


	/**
	 * Supprime un paramètre de l'URL.
	 * 
	 * @param string $name Le nom du paramètre.
	 * @return string L'URL sans le paramètre.
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