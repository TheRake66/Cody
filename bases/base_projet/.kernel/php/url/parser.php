<?php
namespace Kernel\URL;



/**
 * Librairie gerant les parties de l'url
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\URL
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
	
}

?>