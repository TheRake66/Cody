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
            self::logo();
        }
        if ($conf->auto_update) {
            //self::logo();
        }
        while (true) {
            self::prompt();
            $line = readline();
            if ($line !== '') {
                if ($conf->enable_history) {
                    readline_add_history($line);
                }
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
            if ($char[$i] == '"' || $char[$i] == "'") {
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


    /**
     * Affiche la liste des commandes disponibles.
     * 
     * @return void
     */
    static function help() {
        Output::printLn(
"* help                            Affiche la liste des commandes disponible.
api [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un module d'API avec le nom spécifié.
build                           Construit le projet, minifie et compile les fichiers. Nécessite npm.
* cd [*chemin]                    Change le dossier courant ou affiche la liste des fichiers et des dossiers
                                du dossier courant.
* cls                             Nettoie la console.
com [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un composant (controleur, vue, style,
                                script) avec le nom spécifié.
* bye                             Quitte Cody en fermant le serveur PHP si il y en a un.
* dl [url] [chemin]               Télécharge un fichier avec l'URL spécifiée.
* exp                             Ouvre le projet dans l'explorateur de fichiers.
lib [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime une librairie (PHP, LESS, et JavaScript).
                                avec le nom spécifié.
* ls                              Affiche la liste des projets.
maj                             Vérifie les mises à jour disponibles.
new [nom]                       Créer un nouveau projet avec le nom spécifié puis défini le dossier courant.
obj [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un objet (classe DTO, classe DAO)
                                avec le nom spécifié.
pkg [-t|-l|-s] [*nom]           Télécharge, liste ou supprime un package depuis le dépôt de Cody.
* rep                             Ouvre la dépôt GitHub de Cody.
* run [-f]                        Lance un serveur PHP et ouvre le projet dans le navigateur. Si l'option '-f'
                                est ajouté, tous les processus PHP seront arrêté, sinon seul le processus
                                démarrer par Cody sera arrêté.
* stop [-f]                       Arrête le serveur PHP. L'option '-f' arrête tous les processus PHP.
tes [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime une classe de test unitaire.
tra [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un trait.
unit                            Lance les tests unitaires.
* vs                              Ouvre le projet dans Visual Studio Code.
* init
schem

* : Argument facultatif.");
    }

}

?>