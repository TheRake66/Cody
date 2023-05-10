<?php

namespace Kernel\Session;

use Kernel\Security\Configuration;
use Kernel\Debug\Log;
use Kernel\Debug\Error;
use Kernel\Security\Cookie;



/**
 * Librairie gérant la session coté serveur.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.3.0.0
 * @package Kernel\Session
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Socket {

	/**
	 * Initialise la session.
     * 
     * @return void
     * @throws Error Si la session n'a pas pu être initialisée ou régénérée (erreur de session ou de configuration).
	 */
	static function start() {
        $conf = Configuration::get()->session;
		if ($conf->openning) {
            
            if (!is_writable(session_save_path())) {
                Error::trigger('Le répertoire utilisé pour enregistrer les données de session n\'est pas accessible.');
            }

            if (!self::exist()) {
                Log::progress('Démarrage de la session...');

                if ($conf->multiple) {
                    $name = str_replace(' ', '_', $conf->name);
                    if (!session_name($name)) {
                        Error::trigger('Impossible de définir le nom de la session.');
                    }
                }

                if (Cookie::session()) {
                    if (session_start()) {
                        Log::good('Session démarrée.');
                    } else {
                        Error::trigger('Impossible de démarrer la session.');
                    }
                } else {
                    Error::trigger('Impossible de définir les paramètres du cookie de session.');
                }
                
            }

            $error = false;
			$conf = $conf->regenerate;
            if ($conf->delay === 0) {
                $error = !self::regenerate();
            } elseif ($conf->delay > 0) {
                if (!isset($_SESSION['session_last_regenerate'])) {
                    $_SESSION['session_last_regenerate'] = time();
                } elseif (time() - $_SESSION['session_last_regenerate'] > $conf->delay) {
                    $error = !self::regenerate();
                }
            }

            if ($error) {
                Error::trigger('Impossible de régénérer la session.');
            }
		}
	}


    /**
     * Vérifie si la session est démarrée.
     * 
     * @return bool True si la session est démarrée, false sinon.
     */
    static function exist() {
        return session_id() !== '' &&
            isset($_SESSION) &&
            session_status() !== PHP_SESSION_NONE;
    }


    /**
     * Écrit les données de session et ferme la session.
     * 
     * @return void
     * @throws Error Si la session n'a pas pu être libérée.
     */
    static function close() {
        if (session_write_close()) {
            Log::add('Session libérée.');
        } else {
            Error::trigger('Impossible de libérer la session.');
        }
    }


    /**
     * Supprime toutes les données de session puis la ferme.
     * 
     * @return void
     * @throws Error Si la session n'a pas pu être détruite.
     */
    static function clear() {
        if (Cookie::session(true)) {
            if (session_destroy()) {
                Log::add('Session détruite.');
            } else {
                Error::trigger('Impossible de détruire la session.');
            }
        } else {
            Error::trigger('Impossible de détruire les paramètres du cookie de session.');
        }
    }


    /**
     * Régénère la session.
     * 
     * @return bool True si la session a été régénérée, false sinon.
     */
    static function regenerate() {
        $conf = Configuration::get()->session->regenerate;
        return session_regenerate_id($conf->delete_old);
    }

}

?>