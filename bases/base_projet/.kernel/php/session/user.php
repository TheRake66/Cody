<?php
namespace Kernel\Session;

use Kernel\Security\Vulnerability\CSRF;
use Kernel\Security\Cookie;
use Kernel\Debug\Log;



/**
 * Librairie gerant la session de l'utilisateur
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Session
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class User {
    
    /**
     * Creer une session de connexion pour un utilisateur
     * 
     * @param object instance DTO de l'utilisateur a memoriser
     * @param string|null son jeton, si null un nouveau sera generer
     * @return bool si la creation a reussie
     */
    static function login($user, $token = null) {
        if (Token::set($token)) {
            self::set($user);
            Log::add('Utilisateur connecté (jeton : "' . Token::get() . '") : "' . print_r($user, true) . '".', Log::LEVEL_GOOD);
            return true;
        } else {
            Log::add('Impossible de connecter l\'utilisateur !', Log::LEVEL_ERROR);
            return false;
        }
    }


    /**
     * Detruit une session utilisateur
     * 
     * @return bool si la destruction a reussie
     */
    static function logout() {
        if (Token::remove()) {
            self::remove();
            Log::add('Utilisateur déconnecté.', Log::LEVEL_GOOD);
            return true;
        } else {
            Log::add('Impossible de déconnecter l\'utilisateur !', Log::LEVEL_ERROR);
            return false;
        }
    }

    
    /**
     * Defini une session utilisateur
     * 
     * @param object objet DTO de l'utilisateur a memoriser
     * @return void
     */
	static function set($user) {
		$_SESSION['session_user'] = $user;
	}


    /**
     * Recupere la session utilisateur
     * 
     * @return object instance DTO utilisateur en memoire
     */
	static function get() {
		return $_SESSION['session_user'] ?? null;
	}


    /**
     * Detruit la session utilisateur
     * 
     * @return void
     */
	static function remove() {
        unset($_SESSION['session_user']);
	}

    
    /**
     * Verifi si une session utilisateur existe
     * 
     * @return bool si elle existe
     */
	static function has() {
		return isset($_SESSION['session_user']);
	}

}

?>