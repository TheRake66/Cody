<?php
namespace Kernel;



/**
 * Librairie gerant les URL
 */
class Url {

	/**
	 * Accede a une url
	 * 
	 * @param string l'url
     * @return void
	 */
	static function location($url) {
		Stream::clean();
		header('Location: ' . $url);
		Stream::close();
		exit;
	}

	/**
	 * Accede a une url dans l'appli
	 * 
	 * @param string la route
	 * @param array les params
	 * @param string le back
     * @return void
	 */
	static function go($route, $param = [], $addback = false) {
		self::location(self::build($route, $param, $addback));
	}
	

	/**
	 * Recharge la page
	 * 
     * @return void
	 */
	static function reload() {
		self::location(self::current());
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
	 * Retourne le protocol actuel (http ou https)
	 * 
	 * @return string le protocol
	 */
	static function protocol() {
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
	}

	
	/**
	 * Retourne l'adresse du serveur (https://localhost:6600)
	 * 
	 * @return string l'adresse
	 */
	static function host() {
		return self::protocol() . '://' . $_SERVER['HTTP_HOST'];
	}

	
	/**
	 * Retourne l'url actuelle
	 * 
	 * @return string l'url
	 */
	static function current() {
		return self::host() . $_SERVER['REQUEST_URI'];
	}


	/**
	 * Retourne l'url sans les parametres
	 * 
	 * @return string l'url sans les parametres
	 */
	static function root() {
		return self::host() . $_SERVER['PHP_SELF'];
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
		$url = self::root() . '?routePage=' . $route;
		foreach ($param as $name => $value) {
			$url .= '&' . $name . '=' . urlencode($value ?? '');
		}
		if ($addback) {
			$url .= '&redirectUrl=' . urlencode(self::current());
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
		return self::root() . '?' . http_build_query($query);
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
		return self::root() . '?' . http_build_query($query);
	}

}

?>