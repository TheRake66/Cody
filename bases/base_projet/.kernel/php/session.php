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
     * @throws Error si la session n'a pas pu etre initialisee ou regeneree (erreur de session ou de configuration)
	 */
	static function start() {
        $conf = Configuration::get()->session;
		if ($conf->open_session) {
            if (session_status() === PHP_SESSION_NONE) {
                Debug::log('Démarrage de la session...', Debug::LEVEL_PROGRESS);
                if ($conf->multiple_session) {
                    $name = str_replace(' ', '_', $conf->session_name);
                    if (!session_name($name)) {
                        Error::trigger('Impossible de définir le nom de la session.');
                    }
                }
                if (Security::setSessionCookie()) {
                    if (session_start()) {
                        Debug::log('Session démarrée.', Debug::LEVEL_GOOD);
                    } else {
                        Error::trigger('Impossible de démarrer la session.');
                    }
                } else {
                    Error::trigger('Impossible de définir les paramètres du cookie de session.');
                }
            }
            $error = false;
            if ($conf->regenerate_delay === 0) {
                $error = !self::regenerate();
            } elseif ($conf->regenerate_delay > 0) {
                if (!isset($_SESSION['session_last_regenerate'])) {
                    $_SESSION['session_last_regenerate'] = time();
                } elseif (time() - $_SESSION['session_last_regenerate'] > $conf->regenerate_delay) {
                    $error = !self::regenerate();
                }
            }
            if ($error) {
                Error::trigger('Impossible de régénérer la session.');
            }
		}
	}


    /**
     * Regenere la session
     * 
     * @return bool si la regeneration a reussie
     */
    static function regenerate() {
        $conf = Configuration::get()->session;
        return session_regenerate_id($conf->regenerate_delete_old);
    }

    
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
			$token = Security::makeCSRFToken();
		}
        if (Security::setCookie('session_token', $token, time() + $nbdays*60*60*24)) {
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
		return Security::getCookie('session_token');
	}


    /**
     * Detruit le jeton de connexion
     * 
     * @return bool si le jeton a ete detruit
     */
	static function removeToken() {
        if (Security::removeCookie('session_token')) {
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
        return Security::hasCookie('session_token');
    }

}

?>