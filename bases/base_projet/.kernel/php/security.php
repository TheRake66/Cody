<?php
namespace Kernel;
use Kernel\Error;



/**
 * Librairie gerant la securite
 */
class Security {

	/**
	 * Verifie et active le protocole SSL
	 * 
	 * @return void
	 */
	static function enableSsl() {
		if (Configuration::get()->security->redirect_to_https) {
			if($_SERVER['SERVER_PORT'] !== 443 &&
				(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
				Url::location('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			} else {
				Debug::log('SSL actif.');
			}
		}
	}


	/**
     * Defini de maniere securise un cookie
     * 
     * @param string le nom du cookie
     * @param string la valeur du cookie
     * @param string le timestamp correspondant a l'expiration du cookie
	 * @return void
	 */
	static function setCookie($name, $value = '', $time = 0) {
        $conf = Configuration::get()->security;
		setcookie(
            self::getRealCookieName($name), 
            $value, 
            $time, 
            $conf->token_path,
            $conf->token_domain,
            $conf->token_only_https,
            $conf->token_prevent_xss
        );
	}


	/**
     * Supprime un cookie
     * 
     * @param string le nom du cookie
	 * @return void
	 */
	static function deleteCookie($name) {
		$name = self::getRealCookieName($name);
		if (isset($_COOKIE[$name])) {
			unset($_COOKIE[$name]); 
			setcookie($name, null, -1, '/'); 
			return true;
		} else {
			return false;
		}
	}


	/**
     * Recupere un cookie
     * 
     * @param string le nom du cookie
     * @return any la valeur du cookie
	 */
	static function getCookie($name) {
		return $_COOKIE[self::getRealCookieName($name)] ?? null;
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
	
	
    /**
     * Genere un jeton aleatoire de taille n
     * 
     * @param int taille du token
     * @param string le jeu de caracteres
     * @return string le token
     */
	static function makeToken($size, $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
		$token = '';
		for ($i = 0; $i < $size; $i++) {
		   $token .= $charset[rand(0, strlen($charset) - 1)];
		}
		return $token;
	}


	/**
	 * Authentifie un utilisateur via un serveur LDAP
	 * 
	 * @param string l'identifiant de l'utilisateur
	 * @param string le mot de passe de l'utilisateur
	 * @param string le dn (distinguished name)
	 * @param string le serveur
	 * @param int le port
	 * @return bool si les identifiants sont bon
	 * @throws Error si l'extension LDAP n'est pas installee
	 */
	static function authLDAP($login, $password, $dn, $host, $port = 389) {
		if (extension_loaded('ldap') && extension_loaded('openssl')) {
			$response = false;
			if ($con = ldap_connect($host, $port)) {
				ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($con, LDAP_OPT_REFERRALS, 0);
				Error::remove();
				$response = ldap_bind($con, $dn . '\\' . $login, $password);
				Error::handler();
				ldap_close($con);
			}
			return $response;
		} else {
			Error::trigger('Les extensions "ldap" et "openssl" ne sont pas activées !');
		}
	}

}

?>