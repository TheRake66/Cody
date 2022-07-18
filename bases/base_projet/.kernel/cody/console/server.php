<?php
namespace Cody\Console;

use Cody\Io\Environnement;
use Cody\Io\Thread;

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
        if (!self::has()) {
            Output::printLn('Lancement du serveur...');
            $process = Thread::open('php -S localhost:6600');
            if ($process) {
                self::$process = $process;
                Thread::open('start http://localhost:6600/index.php');
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
        if (self::has()) {
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