<?php
namespace Cody\Console\Tool;

use Cody\Console\Output;
use Kernel\Io\Thread;
use Kernel\Security\Configuration;

/**
 * Librairie gérant PHP.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console\Tool
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Php {

    /**
     * @var array Les informations du processus du serveur.
     */
    static $process;
    

    /**
     * Vérifie si le serveur est lancé.
     * 
     * @return bool Si le serveur est lancé.
     */
    static function running() {
        return !is_null(self::$process);
    }


    /**
     * Lance le serveur PHP.
     * 
     * @return bool|null True si le serveur à été lancé, False sinon. 
     * Null si le serveur est déjà lancé.
     */
    static function start() {
        if (!self::running()) {
            Output::printLn('Lancement du serveur...');
            $conf = Configuration::get()->console;
            $server = $conf->php_default_ip . ':' . $conf->php_default_port;
            $process = Thread::open('php -S ' . $server);
            if ($process) {
                self::$process = $process;
                Thread::open('start http://' . $server . '/index.php');
                Output::printLn('Serveur lancé !');
            } else {
                Output::printLn('Erreur lors du lancement du serveur !');
            }
        } else {
            Output::printLn('Le serveur est déjà lancé !');
        }
    }


    /**
     * Arrête le serveur PHP.
     * 
     * @return bool|null True si le serveur à été arrêté, False sinon. 
     * Null si le serveur n'est pas déjà lancé.
     */
    static function stop() {
        if (self::running()) {
            Output::printLn('Arrêt du serveur...');
            if (Thread::kill(self::$process)) {
                self::$process = null;
                Output::printLn('Serveur arrêté !');
            } else {
                Output::printLn('Erreur lors de l\'arrêt du serveur !');
            }
        } else {
            Output::printLn('Le serveur n\'est pas lancé !');
        }
    }

}

?>