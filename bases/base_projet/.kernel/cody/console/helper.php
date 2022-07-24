<?php
namespace Cody\Console;



/**
 * Librairie gérant l'aide des commandes.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Helper {

    /**
     * Aide de la commande "rep".
     * 
     * @return void
     */
    static function rep() {
        Output::usage(
            'Ouvre le dépôt de Cody dans GitHub.', 
            null,
            'La commande va ouvrir l\'URL "https://github.com/TheRake66/Cody" dans le navigateur.'
        );
    }


    /**
     * Aide de la commande "help".
     * 
     * @return void
     */
    static function help() {
        Output::usage(
            'Affiche la liste des commandes disponibles.', [
                'commande' => [ 
                    'Nom d\'une commande, pour afficher l\'aide détaillée de cette commande.' , 
                    false 
                ]
            ],
            'La commande "help run" va afficher l\'aide détaillée de la commande "run".'
        );
    }


    /**
     * Aide de la commande "init".
     * 
     * @return void
     */
    static function init() {
        Output::usage(
            'Initialise le projet avec les variables d\'environnement.', 
            null,
            'La commande va éditer le fichier "project.json" et ".kernel/configuration.json" pour remplacer les variable "{PROJECT_NAME}" par le nom du dossier du projet.'
        );
    }


    /**
     * Aide de la commande "ls".
     * 
     * @return void
     */
    static function ls() {
        Output::usage(
            'Affiche la liste des projets.', 
            null,
            'La commande va lister tous les projets du dossier courant, en listant leur nom, leur taille, leur date de création, etc...'
        );
    }


    /**
     * Aide de la commande "dl".
     * 
     * @return void
     */
    static function dl() {
        Output::usage(
            'Télécharge un fichier avec l\'URL et le chemin spécifié.', [
                'url' => [ 
                    'L\'URL du fichier à télécharger.' , 
                    true 
                ],
                'chemin' => [ 
                    'Le chemin de destination du fichier.' , 
                    true 
                ]
            ],
            'La commande "dl https://www.php.net/distributions/php-8.1.8.tar.gz php8.tar.gz" va télécharger la version 8.1.8 de PHP dans le fichier php8.tar.gz du dossier courant.'
        );
    }


    /**
     * Aide de la commande "bye".
     * 
     * @return void
     */
    static function bye() {
        Output::usage('Quitte Cody en fermant le serveur PHP si il y en a un.');
    }


    /**
     * Aide de la commande "cls".
     * 
     * @return void
     */
    static function cls() {
        Output::usage('Efface le contenu de la console.');
    }


    /**
     * Aide de la commande "run".
     * 
     * @return void
     */
    static function run() {
        Output::usage(
            'Lance un serveur PHP et ouvre le projet dans le navigateur.',
            null,
            'La commande "run" utilise les paramètres de la section "console" du fichier ".kernel/configuration.json" pour lancer le serveur PHP.'
        );
    }


    /**
     * Aide de la commande "stop".
     * 
     * @return void
     */
    static function stop() {
        Output::usage('Ferme le serveur PHP.');
    }


    /**
     * Aide de la commande "vs".
     * 
     * @return void
     */
    static function vs() {
        Output::usage(
            'Ouvre le dossier du projet dans Visual Studio Code.', 
            null,
            'La commande "vs" va ouvrir le dossier du projet dans Visual Studio Code.'
        );
    }


    /**
     * Aide de la commande "exp".
     * 
     * @return void
     */
    static function exp() {
        Output::usage(
            'Ouvre le dossier du projet dans l\'explorateur de fichiers.', 
            null,
            'La commande "exp" va ouvrir le dossier du projet dans l\'explorateur de fichiers.'
        );
    }


    /**
     * Aide de la commande "root".
     * 
     * @return void
     */
    static function root() {
        Output::usage(
            'Change le dossier courant par celui du projet.', 
            null,
            'La commande "root" va changer le dossier courant par le dossier du projet.'
        );
    }


    /**
     * Aide de la commande "cd".
     * 
     * @return void
     */
    static function cd() {
        Output::usage(
            'Change le dossier courant par celui spécifié.', [
                'chemin' => [ 
                    'Le chemin du dossier à aller.' ,
                    false 
                ]
            ],
            'La commande "cd .." va changer le dossier courant par le dossier parent.
La commande "cd" va afficher la liste des fichiers et des dossiers du dossier courant. En bleu, les dossiers. En cyan, les fichiers. En magenta, les dossier qui sont des projets Cody.'
        );
    }


    /**
     * Aide de la commande "conf".
     * 
     * @return void
     */
    static function conf() {
        Output::usage(
            'Recharge la configuration du framework.', 
            null,
            'La commande "conf" va recharger la configuration du framework, cela permet de mettre à jour les comme les paramètres du serveur, ou la taille de la console.'
        );
    }

}

?>