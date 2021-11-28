<?php

namespace Librairie;



class Url {


	/**
	 * Accede a une url
	 * 
	 * @param string la route
	 * @param array les param
	 * @param string le back
	 * @return string le nouvel url
	 */
	static function go($route, $param = [], $addback = false) {
		header('Location: ' . self::build($route, $param, $addback));
		exit;
	}


	/**
	 * Contruit une url
	 * 
	 * @param string la route
	 * @param array les param
	 * @param string le back
	 * @return string le nouvel url
	 */
	static function build($route, $param = [], $addback = false) {
		$url = '?redirect=' . $route;

		foreach ($param as $name => $value) {
			$url .= '&' . $name . '=' . urlencode($value);
		}

		if ($addback) {
			$url .= '&back=' . urlencode($_SERVER['REQUEST_URI']);
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
	static function changeGet($param, $remplace) {
		$query = $_GET;
		$query[$param] = $remplace;
		return $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
	}


	/**
	 * Supprime un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @return string le nouvel url
	 */
	static function removeGet($param) {
		$query = $_GET;
		unset($query[$param]);
		return $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
	}

}

?>