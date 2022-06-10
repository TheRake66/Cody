<?php
namespace Kernel\Session;

use Kernel\Security\Vulnerability\Csrf;
use Kernel\Security\Cookie;
use Kernel\Debug\Log;
use Kernel\Security\Configuration;



/**
 * Librairie gérant le jeton de session.
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
     * Définit le jeton.
     * 
     * @param string|null Le jeton à définir, si NULL, le jeton est généré.
     * @return bool True si le jeton a été défini, false sinon.
     */
	static function set($token = null) {
		if (is_null($token)) {
			$token = self::generate();
		}
        $nbdays = Configuration::get()->session->token_lifetime_days;
        return Cookie::set('session_token', $token, time() + $nbdays*60*60*24);
	}


    /**
     * Étends l'expiration du jeton.
     * 
     * @return bool Si le jeton a été défini et qu'il existe.
     */
    static function extends() {
        if (self::has()) {
            return self::set(self::get());
        } else {
            return false;
        }
    }


    /**
     * Génère un jeton.
     * 
     * @return string Le jeton généré.
     */
    static function generate() {
        return Csrf::generate(100);
    }


    /**
     * Retourne le jeton.
     * 
     * @return string|null Le jeton, NULL si il n'existe pas.
     */
	static function get() {
		return Cookie::get('session_token');
	}


    /**
     * Détruit le jeton de.
     * 
     * @return bool True si le jeton a été détruit, false sinon.
     */
	static function remove() {
        return Cookie::remove('session_token');
	}


    /**
     * Vérifie si le jeton existe.
     * 
     * @return bool True si le jeton existe, false sinon.
     */
    static function has() {
        return Cookie::has('session_token');
    }

}

?>