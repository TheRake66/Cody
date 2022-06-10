<?php
namespace Kernel\Url;



/**
 * Librairie gérant les parties de l'URL.
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
	 * Retourne l'URL de redirection.
	 * 
	 * @return string L'URL de redirection.
	 */
	static function back() {
		return $_GET['redirect_url'] ?? null;
	}

	
	/**
	 * Retourne le protocol actuel (http ou https).
	 * 
	 * @return string Le protocol actuel.
	 */
	static function protocol() {
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
	}

	
	/**
	 * Retourne l'adresse du serveur.
	 * 
	 * @return string L'adresse du serveur.
	 */
	static function host() {
		return self::protocol() . '://' . $_SERVER['HTTP_HOST'];
	}


	/**
	 * Retourne l'URL sans les paramètres.
	 * 
	 * @return string L'URL sans les paramètres.
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
	 * Retourne le chemin de l'URL.
	 * 
	 * @return string Le chemin de l'URL.
	 */
	static function path() {
		return self::root() . Router::asked();
	}

	
	/**
	 * Retourne l'URL actuelle.
	 * 
	 * @return string l'URL actuelle.
	 */
	static function current() {
		return self::host() . $_SERVER['REQUEST_URI'];
	}
	
}

?>