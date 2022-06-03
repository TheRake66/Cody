<?php
namespace Kernel\Url;



/**
 * Librairie gerant les parties de l'url
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Url
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Parser {

	/**
	 * Retourne le parametre de retour
	 * 
	 * @return string le retour
	 */
	static function back() {
		return $_GET['redirect_url'] ?? null;
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
	 * Retourne l'url sans les parametres
	 * 
	 * @return string l'url sans les parametres
	 */
	static function root() {
		$_ = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
		if ($_ === '/') {
			return self::host();
		} else {
			return self::host() . $_;
		}
	}


	/**
	 * Retourne le chemin de l'url
	 * 
	 * @return string le chemin
	 */
	static function path() {
		return self::root() . Router::asked();
	}

	
	/**
	 * Retourne l'url actuelle
	 * 
	 * @return string l'url
	 */
	static function current() {
		return self::host() . $_SERVER['REQUEST_URI'];
	}
	
}

?>