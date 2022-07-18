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

    static function main() {
        self::logo();
        while (true) {
            self::prompt();
            $line = readline();
            if ($line == "exit") {
                break;
            }
            echo $line;
        }
    }


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
                                    ~ Version 7.21.65.0 du 7 juillet 2022 ~
                              ~ Copyright © 2022 - Thibault BUSTOS (TheRake66) ~', Output::COLOR_FORE_YELLOW);
        Output::print('


Utilisez la commande "help" pour voir la liste des commandes.


');
    }


}

?>