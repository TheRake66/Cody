<?php
namespace Cody\Console\Tool;

use Cody\Console\Output;
use Cody\Console\Project;
use Kernel\Io\Environnement;
use Kernel\IO\Path;
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
        $conf = Configuration::get()->console;
        $server = $conf->php_default_ip . ':' . $conf->php_default_port;
        $url = 'http://' . $server . '/';

        if (!self::running()) {
            Output::printLn('Lancement du serveur...');
            $process = Path::toogle(function() use ($server) {
                return Thread::open('php -S ' . $server);
            });
            if ($process) sleep(4);
            if ($process && proc_get_status($process)['running']) {
                self::$process = $process;
                Thread::open('start ' . $url);

                Output::successLn('Serveur lancé !');

                Output::break();

                Output::print(' ');
                Output::print(' PHP ', Output::COLOR_FORE_BLACK, Output::COLOR_BACKGROUND_GREEN);
                Output::print(' version ' . phpversion(), Output::COLOR_FORE_GREEN);
                Output::printLn(' — Serveur interne.', Output::COLOR_FORE_DARK_GRAY);

                Output::break();

                Output::print(' ▌ ', Output::COLOR_FORE_DARK_GRAY);
                Output::print('URL      ');
                Output::printLn($url, Output::COLOR_FORE_MAGENTA);
                
                Output::print(' ▌ ', Output::COLOR_FORE_DARK_GRAY);
                Output::print('IP       ');
                Output::printLn($conf->php_default_ip, Output::COLOR_FORE_CYAN);

                Output::print(' ▌ ', Output::COLOR_FORE_DARK_GRAY);
                Output::print('Port     ');
                Output::printLn($conf->php_default_port, Output::COLOR_FORE_CYAN);

                Output::print(' ▌ ', Output::COLOR_FORE_DARK_GRAY);
                Output::print('Dossier  ');
                Output::printLn(Environnement::root(), Output::COLOR_FORE_CYAN);

                Output::break();

                Output::warningLn(' ▶ Ceci est un serveur de développement, pas de production !');
                Output::printLn('   Documentation : https://www.php.net/manual/fr/features.commandline.webserver.php');

            } else {
                Output::errorLn('Erreur lors du lancement du serveur !');
            }
        } else {
            Thread::open('start ' . $url);
            Output::warningLn('Le serveur est déjà lancé !');
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
                Output::successLn('Serveur arrêté !');
            } else {
                Output::errorLn('Erreur lors de l\'arrêt du serveur !');
            }
        } else {
            Output::warningLn('Le serveur n\'est pas lancé !');
        }
    }

}

?>