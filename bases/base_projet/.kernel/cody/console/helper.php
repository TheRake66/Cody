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
     * Ouvre le dépôt de Cody dans GitHub.
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
     * Affiche la liste des commandes disponibles.
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
     * Initialise un project.
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
     * Liste les projets du dossier courant.
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
     * Télécharge un fichier depuis l'URL spécifiée.
     * 
     * @return void
     */
    static function dl() {
        Output::usage(
            'Télécharge un fichier avec l\'URL spécifiée.', [
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
     * Quitte Cody en fermant le serveur PHP si il y en a un.
     * 
     * @return void
     */
    static function bye() {
    }


    /**
     * Nettoie la console.
     * 
     * @return void
     */
    static function cls() {
    }


    /**
     * Lance le serveur PHP et ouvre le projet dans le navigateur.
     * 
     * @return void
     */
    static function run() {
    }


    /**
     * Ferme le serveur PHP.
     * 
     * @return void
     */
    static function stop() {
    }


    /**
     * Ouvre le dossier courant dans Visual Studio Code.
     * 
     * @return void
     */
    static function vs() {
    }


    /**
     * Ouvre le dossier courant dans l'explorateur de fichiers.
     * 
     * @return void
     */
    static function exp() {
    }


    /**
     * Change le dossier courant par celui du projet.
     * 
     * @return void
     */
    static function root() {
    }


    /**
     * Change le dossier courant par celui spécifié. 
     * Ou affiche la liste des fichiers et des dossiers du dossier courant.
     * 
     * @return void
     */
    static function cd() {
    }


    /**
     * Recharge la configuration du framework.
     * 
     * @return void
     */
    static function conf() {
    }


}

?>