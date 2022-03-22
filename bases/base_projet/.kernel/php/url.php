<?php
namespace Kernel;



/**
 * Librairie gerant les URL
 */
class Url {

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
	 * Recharge la page
	 */
	static function reload() {
		header('Location: ' . self::current());
		exit;
	}


	/**
	 * Retourne le parametre de retour
	 * 
	 * @return string le retour
	 */
	static function back() {
		return $_GET['redirectUrl'] ?? '';
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
		$url = '/index.php?routePage=' . $route;

		foreach ($param as $name => $value) {
			$url .= '&' . $name . '=' . urlencode($value ?? '');
		}

		if ($addback) {
			$url .= '&redirectUrl=' . urlencode($_SERVER['REQUEST_URI']);
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
	static function changeGet($name, $value) {
		$query = $_GET;
		$query[$name] = $value;
		return $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
	}
	

	/**
	 * Ajoute un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @param string sa valeur
	 * @return string le nouvel url
	 */
	static function addGet($name, $value = true) {
		return self::changeGet($name, $value);
	}
	

	/**
	 * Retourne un parametre passe en GET
	 * 
	 * @param string nom du parametre
	 * @return string valeur du parametre
	 */
	static function paramGet($name) {
		return $_GET[$name] ?? null;
	}


	/**
	 * Supprime un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @return string le nouvel url
	 */
	static function removeGet($name) {
		$query = $_GET;
		unset($query[$name]);
		return $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
	}

}

?>