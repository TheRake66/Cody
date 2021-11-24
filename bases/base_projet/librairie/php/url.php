<?php

namespace Librairie;



class Url {

	/**
	 * Contruit une url
	 * 
	 * @param string la route de destination
	 * @param array les parametre avec nom et valeur
	 * @param bool si on ajoute la memorisation du retour
	 * @return string la nouvelle url
	 */
	public static function build($route, $param = [], $addback = false) {
		$url = '?redirect=' . $route;

		foreach ($param as $name => $value) {
			$url .= '&' . $name . '=' . urlencode($value);
		}

		if ($addback) {
			$url .= '&back=' . $_SERVER['REQUEST_URI'];
		}

		return $url;
	}


	/**
	 * Remplace un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @param string sa nouvelle valeur
	 * @return string le nouvel url
	 */
	public static function changeGet($param, $remplace) {
		$query = $_GET;
		$query[$param] = $remplace;
		return $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
	}
	

	/**
	 * Supprime un paramettre de l'url
	 * 
	 * @param string nom du paramettre
	 * @return string l'url sans le paramettre
	 */
	public static function removeGet($param) {
		$query = $_GET;
		unset($query[$param]);
		return $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
	}
	

	/**
	 * Defini une propriete HTML via la liste des paramettre GET, avec
	 * une valeur par defaut si le paramettre n'existe pas
	 * 
	 * @param string nom du paramettre
	 * @param string valeur par defaut
	 * @param string propriete HTML
	 * @return string la propriete avec la valeur
	 */
	public static function getValue($name, $default = '', $key = 'value') {
		return $key . '="' . ($_GET[$name] ?? $default) . '"';

	}
	

	/**
	 * Defini une propriete HTML via la liste des paramettre POST, avec
	 * une valeur par defaut si le paramettre n'existe pas
	 * 
	 * @param string nom du paramettre
	 * @param string valeur par defaut
	 * @param string propriete HTML
	 * @return string la propriete avec la valeur
	 */
	public static function postValue($name, $default = '', $key = 'value') {
		return $key . '="' . ($_POST[$name] ?? $default) . '"';

	}

}

?>