<?php
namespace Cody\Console;

use Cody\Io\Environnement;
use Kernel\Io\Convert\Memory;
use Kernel\Io\Disk;

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
abstract class Command {

    /**
     * Affiche la liste des commandes disponibles.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function help($args) {
        if (empty($args)) {
            Program::help();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Initialise un project.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function init($args) {
        if (empty($args)) {
            Project::init();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Liste les projets du dossier courant.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function ls($args) {
        if (empty($args)) {
            Project::list();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Télécharge un fichier depuis l'URL spécifiée.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function dl($args) {
        if (count($args) === 2) {
            Explorer::download($args[0], $args[1]);
        } else {
            Output::printLn("Erreur : il faut deux arguments : l'URL et le chemin de destination.");
        }
    }


    /**
     * Quitte Cody en fermant le serveur PHP si il y en a un.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function bye($args) {
        if (empty($args)) {
            Server::stop();
            exit(0);
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Nettoie la console.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function cls($args) {
        if (empty($args)) {
            Output::clear();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Lance le serveur PHP et ouvre le projet dans le navigateur.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function run($args) {
        if (empty($args)) {
            Server::run();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Ferme le serveur PHP.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function stop($args) {
        if (empty($args)) {
            Server::stop();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Ouvre le dossier courant dans Visual Studio Code.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function vs($args) {
        if (empty($args)) {
            Explorer::vscode();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Ouvre le dossier courant dans l'explorateur de fichiers.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function exp($args) {
        if (empty($args)) {
            Explorer::open();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Change le dossier courant par celui du projet.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function root($args) {
        if (empty($args)) {
            Explorer::root();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }


    /**
     * Change le dossier courant par celui spécifié. 
     * Ou affiche la liste des fichiers et des dossiers du dossier courant.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function cd($args) {
        if (count($args) === 1) {
            Explorer::change($args[0]);
        } elseif (empty($args)) {
            Explorer::list();
        } else {
            Output::printLn("Erreur : il n'y a pas de paramètre.");
        }
    }

}


?>