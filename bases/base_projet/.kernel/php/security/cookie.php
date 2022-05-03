<?php
namespace Kernel\Security;
use Kernel\Configuration;



/**
 * Librairie gerant les cookies
 */
class Cookie {

	/**
	 * Defini les parametres du cookie de session
	 * 
	 * @return bool si la definition a reussie
	 */
	static function setSessionCookie() {
        $conf = Configuration::get()->security;
		return session_set_cookie_params(
			$conf->cookie_lifetime, 
			$conf->cookie_path,
			$conf->cookie_domain,
			$conf->cookie_only_https,
			$conf->cookie_prevent_xss
		);
	}


	/**
     * Defini de maniere securise un cookie
     * 
     * @param string le nom du cookie
     * @param string la valeur du cookie
     * @param int|null le timestamp correspondant a l'expiration du cookie, null pour l'expiration de la config
	 * @return bool si l'ecriture du cookie a reussie
	 */
	static function setCookie($name, $value = '', $time = null) {
        $conf = Configuration::get()->security;
		return setcookie(
            self::getRealCookieName($name), 
            $value, 
            $time ?? $conf->cookie_lifetime, 
            $conf->cookie_path,
            $conf->cookie_domain,
            $conf->cookie_only_https,
            $conf->cookie_prevent_xss
        );
	}


	/**
     * Supprime un cookie
     * 
     * @param string le nom du cookie
	 * @return bool|null si le la suppression a reussie, null si le cookie n'existe pas
	 */
	static function removeCookie($name) {
		$name = self::getRealCookieName($name);
		if (isset($_COOKIE[$name])) {
			if (self::setCookie($name, '', time() - 3600)) {
				unset($_COOKIE[$name]); 
				return true;
			} else {
				return false;
			}
		}
	}


	/**
     * Recupere un cookie
     * 
     * @param string le nom du cookie
     * @return mixed la valeur du cookie
	 */
	static function getCookie($name) {
		return $_COOKIE[self::getRealCookieName($name)] ?? null;
	}


	/**
     * Verifie si un cookie existe
     * 
     * @param string le nom du cookie
	 * @return bool si le cookie existe
	 */
	static function hasCookie($name) {
		$name = self::getRealCookieName($name);
		return isset($_COOKIE[$name]);
	}


	/**
     * Recupere le nom complet d'un cookie
     * 
     * @param string le nom du cookie
     * @return string le nom complet du cookie
	 */
	static function getRealCookieName($name) {
		return session_name() . '_' . str_replace(' ', '', $name);
	}

}

?>