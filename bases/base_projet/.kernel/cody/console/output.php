<?php
namespace Cody\Console;



/**
 * Librairie gérant les sorties du terminal (console).
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Output {

	/**
     * @var string Les couleurs du terminal.
     */
    const COLOR_FORE_DEFAULT = "\033[39m";
    const COLOR_FORE_BLACK = "\033[30m";
    const COLOR_FORE_RED = "\033[31m";
    const COLOR_FORE_GREEN = "\033[32m";
    const COLOR_FORE_YELLOW = "\033[33m";
    const COLOR_FORE_BLUE = "\033[34m";
    const COLOR_FORE_MAGENTA = "\033[35m";
    const COLOR_FORE_CYAN = "\033[36m";
    const COLOR_FORE_LIGHT_GRAY = "\033[37m";
    const COLOR_FORE_DARK_GRAY = "\033[90m";
    const COLOR_FORE_LIGHT_RED = "\033[91m";
    const COLOR_FORE_LIGHT_GREEN = "\033[92m";
    const COLOR_FORE_LIGHT_YELLOW = "\033[93m";
    const COLOR_FORE_LIGHT_BLUE = "\033[94m";
    const COLOR_FORE_LIGHT_MAGENTA = "\033[95m";
    const COLOR_FORE_LIGHT_CYAN = "\033[96m";
    const COLOR_FORE_WHITE = "\033[97m";
    const COLOR_BACKGROUND_DEFAULT = "\033[49m";
    const COLOR_BACKGROUND_BLACK = "\033[40m";
    const COLOR_BACKGROUND_RED = "\033[41m";
    const COLOR_BACKGROUND_GREEN = "\033[42m";
    const COLOR_BACKGROUND_YELLOW = "\033[43m";
    const COLOR_BACKGROUND_BLUE = "\033[44m";
    const COLOR_BACKGROUND_MAGENTA = "\033[45m";
    const COLOR_BACKGROUND_CYAN = "\033[46m";
    const COLOR_BACKGROUND_LIGHT_GRAY = "\033[47m";
    const COLOR_BACKGROUND_DARK_GRAY = "\033[100m";
    const COLOR_BACKGROUND_LIGHT_RED = "\033[101m";
    const COLOR_BACKGROUND_LIGHT_GREEN = "\033[102m";
    const COLOR_BACKGROUND_LIGHT_YELLOW = "\033[103m";
    const COLOR_BACKGROUND_LIGHT_BLUE = "\033[104m";
    const COLOR_BACKGROUND_LIGHT_MAGENTA = "\033[105m";
    const COLOR_BACKGROUND_LIGHT_CYAN = "\033[106m";
    const COLOR_BACKGROUND_WHITE = "\033[107m";

    
    /**
     * Affiche du texte dans la console.
     * 
     * @param string $text Le texte à afficher.
     * @param string $fore La couleur du texte.
     * @param string $background La couleur de l'arrière-plan.
     * @return void
     */
    static function print($text, $fore = self::COLOR_FORE_DEFAULT, $background = self::COLOR_BACKGROUND_DEFAULT) {
        echo($fore.$background.$text.self::COLOR_FORE_DEFAULT.self::COLOR_BACKGROUND_DEFAULT);
    }


    /**
     * Affiche du texte dans la console et place le curseur à la ligne suivante.
     * 
     * @param string $text Le texte à afficher.
     * @param string $fore La couleur du texte.
     * @param string $background La couleur de l'arrière-plan.
     * @return void
     */
    static function printLn($text, $fore = self::COLOR_FORE_DEFAULT, $background = self::COLOR_BACKGROUND_DEFAULT) {
        self::print($text, $fore, $background);
        self::break();
    }


    /**
     * Nettoie la console.
     * 
     * @return void
     */
    static function clear() {
        echo("\e[H\e[J");
    }


    /**
     * Saute une ligne.
     * 
     * @return void
     */
    static function break() {
        echo(PHP_EOL);
    }

}

?>