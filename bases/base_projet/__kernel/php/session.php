<?php
// Librairie Session
namespace Kernel;



class Session {

	/**
	 * Initialise la session
	 */
	static function start() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}


    /**
     * Genere un jeton aleatoire de taille n
     * 
     * @param int taille de la chaine
     * @return string chaine aleatoire
     */
	static function makeToken($nbLetters) {
		$randString = '';
		$charUniverse = '!@#$%^&*()_+=-[]{}/<>,.?\\abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		for($i = 0; $i < $nbLetters; $i++) {
		   $randString .= $charUniverse[rand(0, strlen($charUniverse) - 1)];
		}
		return $randString;
	}

    
    /**
     * Creer une session de connexion pour un utilisateur
     * 
     * @param object instance dto de l'utilisateur a memoriser
     * @param string son jeton
     * @param int le nombre de jours de validite du jeton
     */
    static function login($user, $token = null, $nbdays = 31) {
        self::setSession($user);
        self::setToken($token, $nbdays);
    }


    /**
     * Detruit une session utilisateur
     */
    static function logout() {
        self::remSession();
        self::remToken();
    }

    
    /**
     * Defini une session utilisateur
     * 
     * @param object instance dto de l'utilisateur a memoriser
     */
	static function setSession($user) {
		$_SESSION['utilisateur'] = $user;
	}


    /**
     * Recupere la session utilisateur
     * 
     * @return object instance dto utilisateur en memoire
     */
	static function getSession() {
		return $_SESSION['utilisateur'] ?? null;
	}


    /**
     * Detruit la session utilisateur
     */
	static function remSession() {
		session_destroy();
	}

    
    /**
     * Defini un jeton de connexion
     * 
     * @param string son jeton
     * @param int le nombre de jours de validite du jeton
     */
	static function setToken($token = null, $nbdays = 31) {
		if (is_null($token)) {
			$token = self::makeToken(50);
		}
		setcookie("jeton", $token, time() + $nbdays*60*60*24);
	}


    /**
     * Recupere le jeton de connexion
     */
	static function getToken() {
		return $_COOKIE['jeton'] ?? null;
	}


    /**
     * Detruit le jeton de connexion
     * 
     * @return string le jeton de connexion en memoire
     */
	static function remToken() {
		setcookie("jeton", "");
	}

}

?>