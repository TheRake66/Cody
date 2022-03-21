<?php
namespace Kernel;
use Kernel\Security;



/**
 * Librairie gerant la session cote serveur
 */
class Session {

	/**
	 * Initialise la session
	 */
	static function start() {
		if (Configuration::get()->session->open_session && session_status() === PHP_SESSION_NONE) {
			Debug::log('Démarrage de la session...', Debug::LEVEL_PROGRESS);
            if (Configuration::get()->session->multiple_session) {
				$name = str_replace(' ', '_', Configuration::get()->session->session_name);
                session_name($name);
            }
			session_start();
			Debug::log('Session démarrée.', Debug::LEVEL_GOOD);
		}
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
		return isset($_SESSION['user']) && !is_null($_SESSION['user']);
	}

    
    /**
     * Defini une session utilisateur
     * 
     * @param object objet DTO de l'utilisateur a memoriser
     */
	static function setSession($user) {
		$_SESSION['user'] = $user;
	}


    /**
     * Recupere la session utilisateur
     * 
     * @return object instance dto utilisateur en memoire
     */
	static function getSession() {
		return $_SESSION['user'] ?? null;
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
			$token = Security::makeToken(50);
		}
		setcookie("token", $token, time() + $nbdays*60*60*24);
        Debug::log('Jeton de connexion : ' . $token . ', défini pour ' . $nbdays . ' jour(s)...');
	}


    /**
     * Recupere le jeton de connexion
     * 
     * @return string le jeton de connexion en memoire
     */
	static function getToken() {
		return $_COOKIE['token'] ?? null;
	}


    /**
     * Detruit le jeton de connexion
     */
	static function remToken() {
		setcookie("token", "");
        Debug::log('Jeton supprimé.');
	}

}

?>