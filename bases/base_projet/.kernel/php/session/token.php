<?php
namespace Kernel\Session;

use Kernel\Security\Vulnerability\CSRF;
use Kernel\Security\Cookie;
use Kernel\Debug\Log;
use Kernel\Security\Configuration;

/**
 * Librairie gerant le jeton de session
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Session
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Token {
    
    /**
     * Defini un jeton de connexion
     * 
     * @param string|null le jeton, si null un nouveau sera generer
     * @return bool si le jeton a ete defini
     */
	static function set($token = null) {
		if (is_null($token)) {
			$token = self::generate();
		}
        $nbdays = Configuration::get()->session->token_lifetime_days;
        return Cookie::set('session_token', $token, time() + $nbdays*60*60*24);
	}


    /**
     * Etends l'expiration du jeton
     * 
     * @return bool si le jeton a ete defini et qu'il existe
     */
    static function extends() {
        if (self::has()) {
            return self::set(self::get());
        } else {
            return false;
        }
    }


    /**
     * Genere un jeton de connexion
     * 
     * @return string le jeton
     */
    static function generate() {
        return CSRF::generate(100);
    }


    /**
     * Recupere le jeton de connexion
     * 
     * @return string|null le jeton de connexion, null si inexistant
     */
	static function get() {
		return Cookie::get('session_token');
	}


    /**
     * Detruit le jeton de connexion
     * 
     * @return bool si le jeton a ete detruit
     */
	static function remove() {
        return Cookie::remove('session_token');
	}


    /**
     * Verifie si un jeton de connexion existe
     * 
     * @return bool si le jeton existe
     */
    static function has() {
        return Cookie::has('session_token');
    }

}

?>