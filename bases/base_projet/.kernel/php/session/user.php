<?php
namespace Kernel\Session;
use Kernel\Security\Cookie;
use Kernel\Security\Vulnerability;
use Kernel\Debug;



/**
 * Librairie gerant la session de l'utilisateur
 */
class User {
    
    /**
     * Creer une session de connexion pour un utilisateur
     * 
     * @param object instance DTO de l'utilisateur a memoriser
     * @param string son jeton
     * @param int le nombre de jours de validite du jeton
     * @return bool si la creation a reussie
     */
    static function login($user, $token = null, $nbdays = 31) {
        if (self::setToken($token, $nbdays)) {
            self::setSession($user);
            return true;
        } else {
            Debug::log('Impossible de créer une session de connexion pour l\'utilisateur !', Debug::LEVEL_ERROR);
            return false;
        }
    }


    /**
     * Detruit une session utilisateur
     * 
     * @return bool si la destruction a reussie
     */
    static function logout() {
        if (self::removeToken()) {
            self::removeSession();
            return true;
        } else {
            Debug::log('Impossible de détruire la session de connexion !', Debug::LEVEL_ERROR);
            return false;
        }
    }

    
    /**
     * Defini une session utilisateur
     * 
     * @param object objet DTO de l'utilisateur a memoriser
     * @return void
     */
	static function setSession($user) {
		$_SESSION['session_user'] = $user;
	}


    /**
     * Recupere la session utilisateur
     * 
     * @return object instance DTO utilisateur en memoire
     */
	static function getSession() {
		return $_SESSION['session_user'] ?? null;
	}


    /**
     * Detruit la session utilisateur
     * 
     * @return void
     */
	static function removeSession() {
        unset($_SESSION['session_user']);
        Debug::log('Session supprimée.');
	}

    
    /**
     * Verifi si une session utilisateur existe
     * 
     * @return bool si elle existe
     */
	static function hasSession() {
		return isset($_SESSION['session_user']) && !is_null($_SESSION['session_user']);
	}

    
    /**
     * Defini un jeton de connexion
     * 
     * @param string le jeton
     * @param int le nombre de jours de validite du jeton
     * @return bool si le jeton a ete defini
     */
	static function setToken($token = null, $nbdays = 31) {
		if (is_null($token)) {
			$token = Vulnerability::makeCSRFToken();
		}
        if (Cookie::setCookie('session_token', $token, time() + $nbdays*60*60*24)) {
            Debug::log('Jeton de connexion : ' . $token . ', défini pour ' . $nbdays . ' jour(s)...');
            return true;
        } else {
            Debug::log('Impossible de définir le jeton de connexion.', Debug::LEVEL_ERROR);
            return false;
        }
	}


    /**
     * Recupere le jeton de connexion
     * 
     * @return string|null le jeton de connexion, null si inexistant
     */
	static function getToken() {
		return Cookie::getCookie('session_token');
	}


    /**
     * Detruit le jeton de connexion
     * 
     * @return bool si le jeton a ete detruit
     */
	static function removeToken() {
        if (Cookie::removeCookie('session_token')) {
            Debug::log('Jeton supprimé.');
            return true;
        } else {
            Debug::log('Impossible de supprimer le jeton.', Debug::LEVEL_ERROR);
            return false;
        }
	}


    /**
     * Verifie si un jeton de connexion existe
     * 
     * @return bool si le jeton existe
     */
    static function hasToken() {
        return Cookie::hasCookie('session_token');
    }

}

?>