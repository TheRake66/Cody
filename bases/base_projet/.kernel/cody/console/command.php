<?php
namespace Cody\Console;

use Cody\Console\Tool\Explorer;
use Cody\Console\Tool\Github;
use Cody\Console\Tool\Php;
use Cody\Console\Tool\Vscode;
use Kernel\Security\Configuration;

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
     * Ouvre le dépôt de Cody dans GitHub.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function rep($args) {
        Argument::empty(function() {
            Github::cody();
        }, $args);
    }


    /**
     * Affiche la liste des commandes disponibles.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function help($args) {
        Argument::empty(function() {
            Output::help();
        }, $args);
    }


    /**
     * Initialise un project.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function init($args) {
        Argument::empty(function() {
            Project::init();
        }, $args);
    }


    /**
     * Liste les projets du dossier courant.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function ls($args) {
        Argument::empty(function() {
            Project::list();
        }, $args);
    }


    /**
     * Télécharge un fichier depuis l'URL spécifiée.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function dl($args) {
        Argument::count(2, function() use ($args) {
            Explorer::download($args[0], $args[1]);
        }, $args);
    }


    /**
     * Quitte Cody en fermant le serveur PHP si il y en a un.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function bye($args) {
        Argument::empty(function() {
            if (Php::running()) {
                Php::stop();
            }
            Output::clear();
            exit(0);
        }, $args);
    }


    /**
     * Nettoie la console.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function cls($args) {
        Argument::empty(function() {
            Output::clear();
        }, $args);
    }


    /**
     * Lance le serveur PHP et ouvre le projet dans le navigateur.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function run($args) {
        Argument::empty(function() {
            Php::start();
        }, $args);
    }


    /**
     * Ferme le serveur PHP.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function stop($args) {
        Argument::empty(function() {
            Php::stop();
        }, $args);
    }


    /**
     * Ouvre le dossier courant dans Visual Studio Code.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function vs($args) {
        Argument::empty(function() {
            Vscode::open();
        }, $args);
    }


    /**
     * Ouvre le dossier courant dans l'explorateur de fichiers.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function exp($args) {
        Argument::empty(function() {
            Explorer::open();
        }, $args);
    }


    /**
     * Change le dossier courant par celui du projet.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function root($args) {
        Argument::empty(function() {
            Explorer::root();
        }, $args);
    }


    /**
     * Change le dossier courant par celui spécifié. 
     * Ou affiche la liste des fichiers et des dossiers du dossier courant.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function cd($args) {
        Argument::match([
            0 => function() {
                Explorer::list();
            },
            1 => function() use ($args) {
                Explorer::change($args[0]);
            }
        ], $args);
    }


    /**
     * Recharge la configuration du framework.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function conf($args) {
        Argument::empty(function() {
            Explorer::reload();
        }, $args);
    }

}

?>