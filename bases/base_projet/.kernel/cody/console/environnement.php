<?php
namespace Cody\Console;



/**
 * Librairie gérant l'environnement du programme.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Environnement {

    /**
     * @var int La largeur maximale de la console.
     */
    const MAX_WINDOW_WIDTH = 80;

    /**
     * @var int La hauteur maximale de la console.
     */
    const MAX_WINDOW_HEIGHT = 25;

    /**
     * @var string Le nom du fichier d'information d'un projet.
     */
    const FILE_PROJECT = 'project.json';

    /**
     * @var string Le fichier de configuration du framework.
     */
    const FILE_CONFIGURATION = '.kernel/configuration.json';

    
    /**
     * Vérifie si le système est Windows.
     * 
     * @return boolean True si le système est Windows, false sinon.
     */
    static function windows() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }


    /**
     * Vérifie si le système est Linux.
     * 
     * @return boolean True si le système est Linux, false sinon.
     */
    static function linux() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'LIN';
    }


    /**
     * Vérifie si le système est Mac.
     * 
     * @return boolean True si le système est Mac, false sinon.
     */
    static function mac() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'MAC';
    }


    /**
     * Vérifie si le système est unix.
     * 
     * @return boolean True si le système est unix, false sinon.
     */
    static function unix() {
        return self::linux() || self::mac();
    }


    /**
     * Retourne la racide du projet.
     * 
     * @return string Le chemin vers la racine du projet.
     */
    static function root() {
        return dirname(dirname(dirname(__DIR__)));
    }


    /**
     * Exécute une commande en arrière-plan.
     * 
     * @param string $cmd La commande à exécuter.
     * @return ressource Les informations du processus.
     */
    static function async($cmd) {
        $desc = [
            ["pipe","r"],
            ["pipe","w"],
            ["pipe","w"],
        ];
        $p = proc_open($cmd, $desc, $pipes);
        return $p;
    }


    /**
     * Termine le processus en arrière-plan.
     * 
     * @param ressource $process Le processus à terminer.
     * @return boolean True si le processus a été terminé, false sinon.
     */
    static function kill($process) {
        if (self::windows()) {
            $status = proc_get_status($process);
            return exec('taskkill /F /T /PID ' . $status['pid']) !== '';
        } else {
            return proc_terminate($process);
        }
    }


    /**
     * Vérifie si un dossier est un dossier de projet.
     * 
     * @param string $dir Le dossier à vérifier.
     * @return boolean True si le dossier est un dossier de projet, false sinon.
     */
    static function project($dir) {
        return file_exists($dir . DIRECTORY_SEPARATOR . self::FILE_PROJECT);
    }


    /**
     * Décode un fichier d'informations.
     * 
     * @param string $file Le fichier d'informations.
     * @param string|null $dir Le dossier du projet. Si null, le dossier du project actuel sera utilisé.
     * @return object|bool|null Les informations du projet, null si le fichier n'existe pas.
     */
    static function decode($file, $dir = null) {
        if ($dir === null) {
            $dir = self::root();
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        if (file_exists($file)) {
            return (object)json_decode(file_get_contents($file), true);
        } else {
            return false;
        }
    }
    

    /**
     * Encode un fichier d'informations.
     * 
     * @param array|object $data Les informations du projet.
     * @param string $file Le fichier d'informations.
     * @param string|null $dir Le dossier du projet. Si null, le dossier du project actuel sera utilisé.
     * @return int|boolean Taille du fichier, false si le fichier n'a pas pu être créé.
     */
    static function encode($data, $file, $dir = null) {
        if ($dir === null) {
            $dir = self::root();
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }


    /**
     * Remplace une variable dans un fichier.
     * 
     * @param string $key La variable à remplacer.
     * @param string $data La valeur à remplacer.
     * @param string $file Le fichier à modifier.
     * @param string|null $dir Le dossier du projet. Si null, le dossier du project actuel sera utilisé.
     * @return int|boolean Taille du fichier, false si le fichier n'a pas pu être créé.
     */
    static function replace($key, $data, $file, $dir = null) {
        if ($dir === null) {
            $dir = self::root();
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        $key = '{' . strtoupper($key) . '}';
        $content = file_get_contents($file);
        $content = str_replace($key, $data, $content);
        return file_put_contents($file, $content);
    }

}

?>