<?php
namespace Cody\Console;



/**
 * Librairie gérant les commandes du programme.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Server {

    /**
     * @var array Les informations du processus du serveur.
     */
    static $process;
    

    /**
     * Vérifie si le serveur est lancé.
     * 
     * @return bool Si le serveur est lancé.
     */
    static function has() {
        return !is_null(self::$process);
    }


    /**
     * Lance le serveur PHP.
     * 
     * @return bool|null True si le serveur à été lancé, False sinon. 
     * Null si le serveur est déjà lancé.
     */
    static function run() {
        if (is_null(self::$process)) {
            $process = Environnement::async('php -S localhost:6600');
            if ($process) {
                self::$process = $process;
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * Arrête le serveur PHP.
     * 
     * @return bool|null True si le serveur à été arrêté, False sinon. 
     * Null si le serveur n'est pas déjà lancé.
     */
    static function stop() {
        if (!is_null(self::$process)) {
            if (Environnement::kill(self::$process)) {
                self::$process = null;
                return true;
            } else {
                return false;
            }
        }
    }

}

?>