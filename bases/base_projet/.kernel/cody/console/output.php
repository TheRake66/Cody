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
     * @var string Les couleurs et les styles du terminal.
     */
    const STYLE_BOLD = "\033[1m";
    const STYLE_DIM = "\033[2m";
    const STYLE_UNDERLINE = "\033[4m";
    const STYLE_BLINK = "\033[5m";
    const STYLE_REVERSE = "\033[7m";
    const STYLE_HIDDEN = "\033[8m";

    const STYLE_RESET_ALL = "\033[0m";
    const STYLE_RESET_BOLD = "\033[21m";
    const STYLE_RESET_DIM = "\033[22m";
    const STYLE_RESET_UNDERLINE = "\033[24m";
    const STYLE_RESET_BLINK = "\033[25m";
    const STYLE_RESET_REVERSE = "\033[27m";
    const STYLE_RESET_HIDDEN = "\033[28m";

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
     * @param string $style Les styles du texte.
     * @return void
     */
    static function print(
            $text, 
            $fore = self::COLOR_FORE_DEFAULT, 
            $background = self::COLOR_BACKGROUND_DEFAULT, 
            $style = self::STYLE_RESET_ALL) {
        echo($style.$fore.$background.$text.self::STYLE_RESET_ALL.self::COLOR_FORE_DEFAULT.self::COLOR_BACKGROUND_DEFAULT);
    }


    /**
     * Affiche du texte dans la console et place le curseur à la ligne suivante.
     * 
     * @param string $text Le texte à afficher.
     * @param string $fore La couleur du texte.
     * @param string $background La couleur de l'arrière-plan.
     * @param string $style Les styles du texte.
     * @return void
     */
    static function printLn(
        $text, 
        $fore = self::COLOR_FORE_DEFAULT, 
        $background = self::COLOR_BACKGROUND_DEFAULT, 
        $style = self::STYLE_RESET_ALL) {
        self::print($text, $fore, $background, $style);
        self::break();
    }

    
    /**
     * Affiche une erreur dans la console.
     * 
     * @param string $text Le texte à afficher.
     * @return void
     */
    static function error($text) {
        self::print($text, self::COLOR_FORE_RED, self::COLOR_BACKGROUND_DEFAULT);
    }

    
    /**
     * Affiche une erreur dans la console et place le curseur à la ligne suivante.
     * 
     * @param string $text Le texte à afficher.
     * @return void
     */
    static function errorLn($text) {
        self::printLn($text, self::COLOR_FORE_RED, self::COLOR_BACKGROUND_DEFAULT);
    }


    /**
     * Affiche un message de succès dans la console.
     * 
     * @param string $text Le texte à afficher.
     * @return void
     */
    static function success($text) {
        self::print($text, self::COLOR_FORE_GREEN, self::COLOR_BACKGROUND_DEFAULT);
    }


    /**
     * Affiche un message de succès dans la console et place le curseur à la ligne suivante.
     * 
     * @param string $text Le texte à afficher.
     * @return void
     */
    static function successLn($text) {
        self::printLn($text, self::COLOR_FORE_GREEN, self::COLOR_BACKGROUND_DEFAULT);
    }


    /**
     * Affiche un message d'avertissement dans la console.
     * 
     * @param string $text Le texte à afficher.
     * @return void
     */
    static function warning($text) {
        self::print($text, self::COLOR_FORE_YELLOW, self::COLOR_BACKGROUND_DEFAULT);
    }


    /**
     * Affiche un message d'avertissement dans la console et place le curseur à la ligne suivante.
     * 
     * @param string $text Le texte à afficher.
     * @return void
     */
    static function warningLn($text) {
        self::printLn($text, self::COLOR_FORE_YELLOW, self::COLOR_BACKGROUND_DEFAULT);
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


    /**
     * Affiche un fichier.
     * 
     * @param string $file Le fichier.
     * @param string $text Le texte 
     * @return void
     */
    static function file($file, $text) {
        Output::print('Fichier: "');
        Output::print($file, Output::COLOR_FORE_CYAN);
        Output::printLn('" ' . $text . '.');
    }


    /**
     * Affiche un dossier.
     * 
     * @param string $dir Le dossier.
     * @param string $text Le texte 
     * @return void
     */
    static function dir($dir, $text) {
        Output::print('Dossier: "');
        Output::print($dir, Output::COLOR_FORE_MAGENTA);
        Output::printLn('" ' . $text . '.');
    }

    
    /**
     * Affiche la ligne de commande.
     * 
     * @return void
     */
    static function prompt() {
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
    static function logo() {
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
                                    ~ Version ' . Program::CODY_VERSION . ' du ' . Program::CODY_RELEASE_DATE . ' ~
                              ~ Copyright © ' . date('Y') . ' - Thibault BUSTOS (TheRake66) ~', Output::COLOR_FORE_YELLOW);
        Output::print('



Utilisez la commande "help" pour voir la liste des commandes.



');
    }


    /**
     * Affiche la liste des commandes disponibles.
     * 
     * @return void
     */
    static function help() {
        Output::printLn(
"+ help [*commande]               Affiche la liste des commandes disponible.
api [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un module d'API avec le nom spécifié.
build                           Construit le projet, minifie et compile les fichiers. Nécessite npm.
+ bye                             Quitte Cody en fermant le serveur PHP si il y en a un.
+ cd [*chemin]                    Change le dossier courant ou affiche la liste des fichiers et des dossiers
                                du dossier courant.
+ cls                             Efface le contenu de la console.
com [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un composant (controleur, vue, style,
                                script) avec le nom spécifié.
+ conf                            Recharge la configuration de Cody.
+ dl [url] [chemin]               Télécharge un fichier avec l'URL spécifiée.
+ exp                             Ouvre le projet dans l'explorateur de fichiers.
+ init                            Initialise le projet avec les variables d'environnement.
lib [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime une librairie (PHP, LESS, et JavaScript).
                                avec le nom spécifié.
+ ls                              Affiche la liste des projets.
maj                             Vérifie les mises à jour disponibles.
obj [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un objet (classe DTO, classe DAO)
                                avec le nom spécifié.
pkg [-t|-l|-s] [*dépôt]         Télécharge, liste ou supprime un package depuis le dépôt passé en
                                paramètre.
+ rep                             Ouvre la dépôt GitHub de Cody.
+ run                             Lance un serveur PHP et ouvre le projet dans le navigateur.
schem [nom]                     Créer tous les objets d'une base de données.
+ stop                            Arrête le serveur PHP.
tes [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime une classe de test unitaire.
tra [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un trait.
unit                            Lance les tests unitaires.
+ vs                              Ouvre le projet dans Visual Studio Code.

* : Argument facultatif.");
    }


    /**
     * Affiche les détails d'une commande.
     * 
     * @param string $usage L'usage de la commande.
     * @param array $options Les options de la commande. [ 'argument' => 'description', ... ]
     * @param array $exemple Les exemples d'utilisation de la commande.
     * @return void
     */
    static function usage($usage, $options = null, $exemple = null) {
        self::printLn('Description:', Output::COLOR_FORE_YELLOW, Output::COLOR_BACKGROUND_DEFAULT, Output::STYLE_UNDERLINE);
        self::printLn('    ' . $usage);
        if ($options) {
            self::break();
            self::printLn('Options:', Output::COLOR_FORE_YELLOW, Output::COLOR_BACKGROUND_DEFAULT, Output::STYLE_UNDERLINE);
            $longest = 0;
            foreach ($options as $arg => [ $desc, $needed ]) {
                $arg = $arg . ($needed ? '' : ' (optionnel)');
                if (strlen($arg) > $longest) {
                    $longest = strlen($arg);
                }
            }
            foreach ($options as $arg => [ $desc, $needed ]) {
                $arg = $arg . ($needed ? '' : ' (optionnel)');
                self::print('    ' . $arg . str_repeat(' ', $longest - strlen($arg)), Output::COLOR_FORE_GREEN);
                self::printLn('  ' . $desc);
            }
        }
        if ($exemple) {
            self::break();
            self::printLn('Exemple:', Output::COLOR_FORE_YELLOW, Output::COLOR_BACKGROUND_DEFAULT, Output::STYLE_UNDERLINE);
            self::printLn('    ' . $exemple);
        }
    }
    
}

?>