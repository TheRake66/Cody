<?php
namespace Kernel\Security;
use Kernel\Error;



/**
 * Librairie gerant la validation des donnees
 */
class Validation {	
	
    /**
     * Genere un jeton aleatoire de taille n
     * 
     * @param int taille du token
     * @param string le jeu de caracteres
     * @return string le token
     */
	static function makeSimpleToken($size = 32, $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
		$token = '';
		$max = strlen($charset) - 1;
		for ($i = 0; $i < $size; $i++) {
		   $token .= $charset[rand(0, $max)];
		}
		return $token;
	}


	/**
	 * Verifie si un mot de passe est robuste
	 * 
	 * @param string le mot de passe
	 * @param int la longueur minimum
	 * @param int la longueur maximum
	 * @param bool si le mot de passe doit contenir une majuscule
	 * @param bool si le mot de passe doit contenir une minuscule
	 * @param bool si le mot de passe doit contenir un chiffre
	 * @param bool si le mot de passe doit contenir un caractere special
	 * @return bool si le mot de passe est robuste
	 */
	static function passwordIsStrong($password, $min = 8, $max = 20, $upper = true, $lower = true, $number = true, $special = true) {
		$len = strlen($password);
		if ((!is_null($min) && $len < $min) || (!is_null($max) && $len > $max)) return false;
		elseif ($upper && !preg_match('/[A-Z]/', $password)) return false;
		elseif ($lower && !preg_match('/[a-z]/', $password)) return false;
		elseif ($number && !preg_match('/[0-9]/', $password)) return false;
		elseif ($special && !preg_match('/[^a-zA-Z0-9]/', $password)) return false;
		else return true;
	}


	/**
	 * Verifie si une adresse email est valide
	 * 
	 * @param string l'adresse email
	 * @return bool si l'adresse email est valide
	 */
	static function emailIsValid($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
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