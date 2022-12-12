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
 * @version 1.0
 * @package Kernel\Session
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
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

            if (session_status() === PHP_SESSION_NONE) {
                Log::add('Démarrage de la session...', Log::LEVEL_PROGRESS);

                if ($conf->multiple) {
                    $name = str_replace(' ', '_', $conf->name);
                    if (!session_name($name)) {
                        Error::trigger('Impossible de définir le nom de la session.');
                    }
                }

                if (Cookie::session()) {
                    if (session_start()) {
                        Log::add('Session démarrée.', Log::LEVEL_GOOD);
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