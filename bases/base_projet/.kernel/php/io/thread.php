<?php
namespace Kernel\Io;



/**
 * Librairie gérant les fils d'exécution.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Thread {

    /**
     * Exécute une commande en arrière-plan.
     * 
     * @param string $cmd La commande à exécuter.
     * @return ressource Les informations du processus.
     */
    static function open($cmd) {
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
        if (Environnement::os() === Environnement::OS_WINDOWS) {
            $status = proc_get_status($process);
            return exec('taskkill /F /T /PID ' . $status['pid']) !== '';
        } else {
            return proc_terminate($process);
        }
    }


    /**
     * Calcul le temps d'exécution d'une fonction.
     * 
     * @param string $function La fonction à mesurer.
     * @return float Le temps d'exécution en millisecondes.
     */
    static function elapsed($callback) {
        $started = microtime(true);
        $callback();
        $ended = microtime(true);
        $time = round(($ended - $started) * 1000);
        return $time;
    }

}

?>