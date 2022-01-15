<?php
// Librairie Session
namespace Kernel;



class Session {

	/**
	 * Initialise la session
     * 
     * @param string le nom de la session, par defaut c'est le nom de domaine
	 */
	static function start($name = null) {
		if (Configuration::get()->ouvrir_session && session_status() === PHP_SESSION_NONE) {
			Debug::log('Démarrage de la session...', Debug::LEVEL_PROGRESS);
            if (Configuration::get()->session_multisite) {
				$name = basename(dirname(dirname(__DIR__)));
            }
            session_name($name);
			session_start();
			Debug::log('Session démarrée.', Debug::LEVEL_GOOD);
		}
	}


    /**
     * Genere un jeton aleatoire de taille n
     * 
     * @param int taille du token
     * @return string le token
     */
	static function makeToken($size) {
		$token = '';
		$charset = '!@#$%^&*()_+=-[]{}/<>,.?\\abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		for ($i = 0; $i < $size; $i++) {
		   $token .= $charset[rand(0, strlen($charset) - 1)];
		}
		return $token;
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
     * Verifi si une session existe
     * 
     * @return bool si elle existe
     */
	static function hasSession() {
		return isset($_SESSION['utilisateur']) && !is_null($_SESSION['utilisateur']);
	}

    
    /**
     * Defini une session utilisateur
     * 
     * @param object objet DTO de l'utilisateur a memoriser
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
        Debug::log('Session supprimée.');
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
        Debug::log('Jeton de connexion : ' . $token . ', défini pour ' . $nbdays . '...');
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
        Debug::log('Jeton supprimé.');
	}

}

?>