<?php
namespace Kernel;
use Kernel\Error;



// Librairie Security
class Security {

	/**
	 * Verifie et active le protocole SSL
	 */
	static function enableSSL() {
		if (Configuration::get()->enable_ssl) {
			if($_SERVER['SERVER_PORT'] !== 443 &&
				(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
				header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				exit;
			} else {
				Debug::log('SSL actif.');
			}
		}
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
	 */
	static function authLDAP($login, $password, $dn, $host, $port = 389) {
		$response = false;
		if ($con = ldap_connect($host, $port)) {
			ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($con, LDAP_OPT_REFERRALS, 0);
			set_error_handler(function() { });
			$response = ldap_bind($con, $dn . '\\' . $login, $password);
			Error::handler();
			ldap_close($con);
		}
		return $response;
	}

}

?>