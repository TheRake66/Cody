<?php
namespace Kernel\Security;

use Kernel\Debug\Error;



/**
 * Librairie gérant la validation des données.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Security
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Validation {	

	/**
	 * Vérifie si un mot de passe est robuste.
	 * 
	 * @param string $password Le mot de passe à vérifier.
	 * @param int $min Le nombre minimum de caractères.
	 * @param int $max Le nombre maximum de caractères.
	 * @param bool $upper Si le mot de passe doit contenir des majuscules.
	 * @param bool $lower Si le mot de passe doit contenir des minuscules.
	 * @param bool $number Si le mot de passe doit contenir des chiffres.
	 * @param bool $special Si le mot de passe doit contenir des caractères spéciaux.
	 * @return bool True si le mot de passe est robuste, false sinon.
	 */
	static function strong($password, $min = 8, $max = 20, $upper = true, $lower = true, $number = true, $special = true) {
		$len = strlen($password);
		if ((!is_null($min) && $len < $min) || (!is_null($max) && $len > $max)) return false;
		elseif ($upper && !preg_match('/[A-Z]/', $password)) return false;
		elseif ($lower && !preg_match('/[a-z]/', $password)) return false;
		elseif ($number && !preg_match('/[0-9]/', $password)) return false;
		elseif ($special && !preg_match('/[^a-zA-Z0-9]/', $password)) return false;
		else return true;
	}


	/**
	 * Vérifie si une adresse email est valide.
	 * 
	 * @param string $email L'adresse email à vérifier.
	 * @return bool True si l'adresse email est valide, false sinon.
	 */
	static function email($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	

	/**
	 * Authentifie un utilisateur via un serveur LDAP.
	 * 
	 * @param string $login Le login de l'utilisateur.
	 * @param string $password Le mot de passe de l'utilisateur.
	 * @param string $dn Le DN (distinguished name).
	 * @param string $host Le serveur LDAP.
	 * @param int $port Le port du serveur LDAP.
	 * @return bool True si l'utilisateur est authentifié, false sinon.
	 * @throws Error Si l'extension LDAP n'est pas installée.
	 */
	static function ldap($login, $password, $dn, $host, $port = 389) {
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