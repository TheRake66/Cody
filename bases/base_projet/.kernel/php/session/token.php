<?php
namespace Kernel\Session;

use Kernel\Security\Vulnerability\CSRF;
use Kernel\Security\Cookie;
use Kernel\Debug\Log;



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
     * @param string le jeton
     * @param int le nombre de jours de validite du jeton
     * @return bool si le jeton a ete defini
     */
	static function set($token = null, $nbdays = 31) {
		if (is_null($token)) {
			$token = self::generate();
		}
        if (Cookie::set('session_token', $token, time() + $nbdays*60*60*24)) {
            Log::add('Jeton de connexion : ' . $token . ', défini pour ' . $nbdays . ' jour(s)...');
            return true;
        } else {
            Log::add('Impossible de définir le jeton de connexion.', Log::LEVEL_ERROR);
            return false;
        }
	}


    /**
     * Creer un jeton de connexion
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
        if (Cookie::remove('session_token')) {
            Log::add('Jeton supprimé.');
            return true;
        } else {
            Log::add('Impossible de supprimer le jeton.', Log::LEVEL_ERROR);
            return false;
        }
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