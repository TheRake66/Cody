<?php
namespace Kernel;
use Kernel\Security;



/**
 * Librairie gerant la session cote serveur
 */
class Session {

	/**
	 * Initialise la session
     * 
     * @return void
	 */
	static function start() {
        $conf = Configuration::get()->session;
		if ($conf->open_session && session_status() === PHP_SESSION_NONE) {
			Debug::log('Démarrage de la session...', Debug::LEVEL_PROGRESS);
            if ($conf->multiple_session) {
				$name = str_replace(' ', '_', $conf->session_name);
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
     * @return void
     */
    static function login($user, $token = null, $nbdays = 31) {
        self::setSession($user);
        self::setToken($token, $nbdays);
    }


    /**
     * Detruit une session utilisateur
     * 
     * @return void
     */
    static function logout() {
        self::remmoveSession();
        self::remmoveToken();
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
     * @return void
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
     * 
     * @return void
     */
	static function remmoveSession() {
		session_destroy();
        Debug::log('Session supprimée.');
	}

    
    /**
     * Defini un jeton de connexion
     * 
     * @param string son jeton
     * @param int le nombre de jours de validite du jeton
     * @return void
     */
	static function setToken($token = null, $nbdays = 31) {
		if (is_null($token)) {
			$token = Security::makeToken(50);
		}
        Security::setCookie('token', $token, time() + $nbdays*60*60*24);
        Debug::log('Jeton de connexion : ' . $token . ', défini pour ' . $nbdays . ' jour(s)...');
	}


    /**
     * Recupere le jeton de connexion
     * 
     * @return string le jeton de connexion en memoire
     */
	static function getToken() {
		return Security::getCookie('token');
	}


    /**
     * Detruit le jeton de connexion
     * 
     * @return void
     */
	static function remmoveToken() {
        Security::deleteCookie('token');
        Debug::log('Jeton supprimé.');
	}

}

?>