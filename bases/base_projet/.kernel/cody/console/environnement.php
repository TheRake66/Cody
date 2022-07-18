<?php
namespace Cody\Console;



/**
 * Librairie gérant l'environnement du programme.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Environnement {

    /**
     * @var int La largeur maximale de la console.
     */
    const MAX_WINDOW_WIDTH = 80;

    /**
     * @var int La hauteur maximale de la console.
     */
    const MAX_WINDOW_HEIGHT = 25;

    
    /**
     * Vérifie si le système est Windows.
     * 
     * @return boolean True si le système est Windows, false sinon.
     */
    static function windows() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }


    /**
     * Vérifie si le système est Linux.
     * 
     * @return boolean True si le système est Linux, false sinon.
     */
    static function linux() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'LIN';
    }


    /**
     * Vérifie si le système est Mac.
     * 
     * @return boolean True si le système est Mac, false sinon.
     */
    static function mac() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'MAC';
    }


    /**
     * Vérifie si le système est unix.
     * 
     * @return boolean True si le système est unix, false sinon.
     */
    static function unix() {
        return self::linux() || self::mac();
    }


    /**
     * Retourne la racide du projet.
     * 
     * @return string Le chemin vers la racine du projet.
     */
    static function root() {
        return dirname(dirname(dirname(__DIR__)));
    }


    /**
     * Exécute une commande en arrière-plan.
     * 
     * @param string $cmd La commande à exécuter.
     * @return ressource Les informations du processus.
     */
    static function async($cmd) {
        $desc = [
            ["pipe","r"],
            ["pipe","w"],
            ["pipe","w"],
        ];
        $p = proc_open($cmd, $desc, $pipes);
        return $p;
    }


    /**
     * Termine le processus en arrière-plan.
     * 
     * @param ressource $process Le processus à terminer.
     * @return boolean True si le processus a été terminé, false sinon.
     */
    static function kill($process) {
        if (self::windows()) {
            $status = proc_get_status($process);
            return exec('taskkill /F /T /PID ' . $status['pid']) !== '';
        } else {
            return proc_terminate($process);
        }
    }

}

?>