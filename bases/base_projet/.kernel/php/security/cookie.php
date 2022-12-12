<?php
namespace Kernel\Security;

use Kernel\Security\Configuration;



/**
 * Librairie gérant les cookies.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Security
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Cookie {

	/**
	 * Définissent les paramètres du cookie de session.
	 * 
	 * @return bool True si les paramètres sont corrects, false sinon.
	 */
	static function session() {
        $conf = Configuration::get()->security->cookies;
		return session_set_cookie_params(
			$conf->lifetime, 
			$conf->path,
			$conf->domain,
			$conf->only_https,
			$conf->prevent_xss
		);
	}


	/**
     * Défini de manière sécurise un cookie.
     * 
     * @param string $name Le nom du cookie.
     * @param string $value La valeur du cookie.
     * @param int|null Le timestamp correspondant à l'expiration du cookie, NULL pour l'expiration de la configuration.
	 * @return bool True si le cookie a été défini, false sinon.
	 */
	static function set($name, $value = '', $time = null) {
        $conf = Configuration::get()->security->cookies;
		return setcookie(
            self::name($name), 
            $value, 
            $time ?? $conf->lifetime, 
            $conf->path,
            $conf->domain,
            $conf->only_https,
            $conf->prevent_xss
        );
	}


	/**
     * Supprime un cookie.
     * 
     * @param string $name Le nom du cookie.
	 * @return bool True si le cookie a été supprimé, false sinon.
	 */
	static function remove($name) {
		if (self::has($name)) {
			if (self::set($name, '', -1)) {
				unset($_COOKIE[self::name($name)]); 
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}


	/**
     * Retourne la valeur d'un cookie.
     * 
     * @param string $name Le nom du cookie.
     * @return mixed La valeur du cookie, NULL si le cookie n'existe pas.
	 */
	static function get($name) {
		return $_COOKIE[self::name($name)] ?? null;
	}


	/**
     * Vérifie si un cookie existe.
     * 
     * @param string $name Le nom du cookie.
	 * @return bool True si le cookie existe, false sinon.
	 */
	static function has($name) {
		return isset($_COOKIE[self::name($name)]);
	}


	/**
     * Retourne le nom complet d'un cookie.
     * 
     * @param string $name Le nom du cookie.
     * @return string Le nom complet du cookie.
	 */
	static function name($name) {
		return session_name() . '_' . str_replace(' ', '', $name);
	}

}

?>