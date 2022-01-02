<?php
// Librairie Url
namespace Kernel;



class Url {

	/**
	 * Recharge la page
	 */
	static function reload() {
		header('Location: ' . self::current());
		exit;
	}
	

	/**
	 * Accede a une url
	 * 
	 * @param string la route
	 * @param array les params
	 * @param string le back
	 */
	static function go($route, $param = [], $addback = false) {
		header('Location: ' . self::build($route, $param, $addback));
		exit;
	}


	/**
	 * Retourne le parametre de retour
	 * 
	 * @return string le retour
	 */
	static function back() {
		return $_GET['back'] ?? '';
	}

	
	/**
	 * Retourne l'url actuelle
	 * 
	 * @return string l'url
	 */
	static function current() {
		return $_SERVER['REQUEST_URI'];
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
	 * Retourne un parametre passe en GET
	 * 
	 * @param string nom du parametre
	 * @return string valeur du parametre
	 */
	static function paramGet($param) {
		return $_GET[$param] ?? null;
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