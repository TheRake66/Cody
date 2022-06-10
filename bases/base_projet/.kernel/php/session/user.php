<?php
namespace Kernel\Session;

use Kernel\Security\Vulnerability\Csrf;
use Kernel\Security\Cookie;
use Kernel\Debug\Log;



/**
 * Librairie gérant la session de l'utilisateur.
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
     * Créer une session pour un utilisateur.
     * 
     * @param object $user Instance DTO de l'utilisateur à mémoriser.
     * @param string|null $token Le jeton de session à utiliser, si NULL, le jeton est généré.
     * @return bool True si la session a été créée, false sinon.
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
     * Détruit une session utilisateur.
     * 
     * @return bool True si la session a été détruite, false sinon.
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
     * Définit une session utilisateur.
     * 
     * @param object $user Instance DTO de l'utilisateur à mémoriser.
     * @return void
     */
	static function set($user) {
		$_SESSION['session_user'] = $user;
	}


    /**
     * Retourne une session utilisateur.
     * 
     * @return object Instance DTO de l'utilisateur, NULL si il n'existe pas.
     */
	static function get() {
		return $_SESSION['session_user'] ?? null;
	}


    /**
     * Détruit une session utilisateur.
     * 
     * @return void
     */
	static function remove() {
        unset($_SESSION['session_user']);
	}

    
    /**
     * Vérifie si une session utilisateur existe.
     * 
     * @return bool True si une session utilisateur existe, false sinon.
     */
	static function has() {
		return isset($_SESSION['session_user']);
	}

}

?>