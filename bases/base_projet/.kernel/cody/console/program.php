<?php
namespace Cody\Console;



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
        self::logo();
        while (true) {
            self::prompt();
            $line = readline();
            if ($line !== '') {
                readline_add_history($line);
                $args = self::parse($line);
                self::dispatch($args);
            } else {
                Output::printLn('Aucune commande, essayer la commande "help".');
            }
            Output::break();
            Output::break();
        }
    }


    /**
     * Exécute la commande demandée.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static private function dispatch($args) {
        $command = array_shift($args);
        if (method_exists(Command::class, $command)) {
            Command::$command($args);
        } else {
            Output::printLn('Commande inconnue, essayer la commande "help".');
        }
    }


    /**
     * Découpe les arguments d'une ligne de commande.
     * 
     * @param string $line Ligne de commande.
     * @return array Tableau des arguments.
     */
    static private function parse($line) {
        $char = str_split($line);
        $quote = false;
        for ($i = 0; $i < count($char); $i++) {
            if ($char[$i] == '"') {
                $quote = !$quote;
            }
            if (!$quote && $char[$i] === ' ') {
                $char[$i] = "\n";
            }
        }
        $args = explode("\n", implode($char));
        return $args;
    }


    /**
     * Affiche la ligne de commande.
     * 
     * @return void
     */
    static private function prompt() {
        Output::print('┌──┤', Output::COLOR_FORE_RED);
        Output::print(getenv('username'), Output::COLOR_FORE_CYAN);
        Output::print('@', Output::COLOR_FORE_YELLOW);
        Output::print(getenv('computername'), Output::COLOR_FORE_BLUE);
        Output::print('├─┤', Output::COLOR_FORE_RED);
        Output::print(getcwd(), Output::COLOR_FORE_GREEN);
        Output::printLn('│', Output::COLOR_FORE_RED);
        Output::print('└────►', Output::COLOR_FORE_RED);
        Output::print(' $', Output::COLOR_FORE_YELLOW);
        Output::print(': ');
    }


    /**
     * Affiche le logo de Cody.
     * 
     * @return void
     */
    static private function logo() {
        Output::clear();
        Output::print('
                                ▄▄▄▄▄▄▄                          ▄▄
                              ▄████████▌▐▄                       ██
                             ████▀▀▀▀▀▀ ▀▀                       ██
                            ▐███ ▐████████▌    ▄▄▄▄▄▄      ▄▄▄▄▄▄██   ▄▄       ▄▄
                            ███▌ ▄▄▄▄▄▄▄▄▄▄   ████████    █████████  ▐██▌     ▐██▌
                            ███▌ ▀▀▀▀▀▀▀▀▀▀  ▐██▀  ▀██▌  ▐██▀  ▀███   ▐██▌   ▐██▌
                            ▐███ ▐████████▌  ██▌    ▐██  ██▌    ▐██    ▐██▌ ▐██▌
                             ████▄▄▄▄▄▄ ▄▄   ▐██▄  ▄██▌  ▐██▄  ▄██▌     ▐██▄██▌
                              ▀████████▌▐▀    ████████    ████████       ▐███▌
                                ▀▀▀▀▀▀▀        ▀▀▀▀▀▀      ▀▀▀▀▀▀        ███▀
                                                                        ███
                                                                       ███
                                                                     ▄███
                                                                     ▀▀▀', Output::COLOR_FORE_CYAN);
        Output::print('
                                            ░░░▒▒▓▓ Cody ▓▓▒▒░░░', Output::COLOR_FORE_RED);
        Output::print('
                                    ~ Version ' . self::CODY_VERSION . ' du ' . self::CODY_RELEASE_DATE . ' ~
                              ~ Copyright © ' . date('Y') . ' - Thibault BUSTOS (TheRake66) ~', Output::COLOR_FORE_YELLOW);
        Output::print('



Utilisez la commande "help" pour voir la liste des commandes.



');
    }

}

?>