<?php
namespace Cody\Console;

use Kernel\Security\Configuration;

/**
 * Librairie gérant la boucle principale du programme.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Program {

    /**
     * @var string Version du framework.
     */
    const CODY_VERSION = "8.21.65.0";

    /**
     * @var string Date de la dernière mise à jour du framework.
     */
    const CODY_RELEASE_DATE = "7 juillet 2022";


    /**
     * Boucle principale du programme.
     * 
     * @return void
     */
    static function main() {
        cli_set_process_title('(' . self::CODY_VERSION . ') Cody Framework');
        Output::clear();
        $conf = Configuration::get()->console;
        if ($conf->print_logo) {
            Output::logo();
        }
        if ($conf->auto_update) {
        }
        while (true) {
            Output::prompt();
            $line = trim(readline());
            if ($line !== '') {
                if ($conf->enable_history) {
                    readline_add_history($line);
                }
                $args = Argument::parse($line);
                $command = array_shift($args);
                if (method_exists(Command::class, $command)) {
                    Command::$command($args);
                } else {
                    Output::errorLn('Commande inconnue, essayez la commande "help".');
                }
            } else {
                Output::warningLn('Aucune commande, essayez la commande "help".');
            }
            Output::break();
            Output::break();
        }
    }

}

?>