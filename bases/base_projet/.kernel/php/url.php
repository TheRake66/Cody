<?php
namespace Kernel;
use Kernel\Html\Import;
use Kernel\Html\Builder;
use Kernel\Html\Output;



/**
 * Librairie gerant les URL
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Url {

	/**
     * @var string Les methodes d'envoie
	 */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';


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
	 * Recharge la page
	 * 
     * @return void
	 */
	static function reload() {
		self::location(self::getCurrent());
	}


	/**
	 * Contruit une url
	 * 
	 * @param string la route
	 * @param array les parametres
	 * @param string si on ajoute le parametre de retour
	 * @return string l'url
	 */
	static function build($route, $params = [], $addBack = false) {
		$url = self::getRoot() . $route;
		if ($addBack) {
			$params['redirect_url'] = self::getCurrent();
		}
		if ($params || $addBack) {
			$url .= '?' . http_build_query($params);
		}
		return $url;
	}


	/**
	 * Accede a une url dans l'application
	 * 
	 * @param string la route vers le composant
	 * @param array les parametres
	 * @param boolean si on ajoute le parametre de retour
	 * @param string la methode (GET, POST)
     * @return void
	 */
	static function go($route, $params = [], $addBack = false, $method = Url::METHOD_GET) {
		if ($method == Url::METHOD_GET) {
			self::location(self::build($route, $params, $addBack));
		} else {
			$html = Builder::create('form', [
				'action' => self::build($route),
				'method' => 'post',
				'id' => 'KERNEL_REDIRECT_FORM'
			]);
			foreach ($params as $key => $value) {
				$html .= Builder::create('input', [
					'type' => 'hidden',
					'name' => $key,
					'value' => $value
				]);
			}
			if ($addBack) {
				$html .= Builder::create('input', [
					'type' => 'hidden',
					'name' => 'redirect_url',
					'value' => self::getCurrent()
				]);
			}
			$html .= Import::runScript('
				let f = document.getElementById("KERNEL_REDIRECT_FORM");
				f.submit();
				f.remove();
			');
			Output::add($html);
		}
	}


	/**
	 * Retourne le parametre de retour
	 * 
	 * @return string le retour
	 */
	static function getBack() {
		return $_GET['redirect_url'] ?? null;
	}

	
	/**
	 * Retourne le protocol actuel (http ou https)
	 * 
	 * @return string le protocol
	 */
	static function getProtocol() {
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
	}

	
	/**
	 * Retourne l'adresse du serveur (https://localhost:6600)
	 * 
	 * @return string l'adresse
	 */
	static function getHost() {
		return self::getProtocol() . '://' . $_SERVER['HTTP_HOST'];
	}


	/**
	 * Retourne l'url sans les parametres
	 * 
	 * @return string l'url sans les parametres
	 */
	static function getRoot() {
		$_ = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
		if ($_ === '/') {
			return self::getHost();
		} else {
			return self::getHost() . $_;
		}
	}


	/**
	 * Retourne le chemin de l'url
	 * 
	 * @return string le chemin
	 */
	static function getPath() {
		return self::getRoot() . Router::getAsked();
	}

	
	/**
	 * Retourne l'url actuelle
	 * 
	 * @return string l'url
	 */
	static function getCurrent() {
		return self::getHost() . $_SERVER['REQUEST_URI'];
	}
	

	/**
	 * Remplace un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @param string sa nouvelle valeur
	 * @return string le nouvel url
	 */
	static function changeParam($name, $value) {
		$query = $_GET;
		$query[$name] = $value;
		return self::getRoot() . '?' . http_build_query($query);
	}
	

	/**
	 * Ajoute un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @param string sa valeur
	 * @return string le nouvel url
	 */
	static function addParam($name, $value) {
		return self::changeParam($name, $value);
	}
	

	/**
	 * Retourne un parametre de l'url
	 * 
	 * @param string nom du parametre
	 * @return string valeur du parametre
	 */
	static function getParam($name) {
		return $_GET[$name] ?? null;
	}


	/**
	 * Supprime un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @return string le nouvel url
	 */
	static function removeParam($name) {
		$query = $_GET;
		unset($query[$name]);
		return self::getRoot() . '?' . http_build_query($query);
	}

}

?>