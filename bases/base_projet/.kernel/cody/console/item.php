<?php
namespace Cody\Console;



/**
 * Librairie gérant les objets du framework.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Project {

    /**
     * @var string Le nom du fichier d'information d'un projet.
     */
    const FILE_PROJECT = 'project.json';

    /**
     * @var string Le fichier de configuration du framework.
     */
    const FILE_CONFIGURATION = '.kernel/configuration.json';


    /**
     * Vérifie si un dossier est un dossier de projet.
     * 
     * @param string $dir Le dossier à vérifier.
     * @return boolean True si le dossier est un dossier de projet, false sinon.
     */
    static function is($dir) {
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
            $dir = Environnement::root();
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
            $dir = Environnement::root();
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
            $dir = Environnement::root();
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        $key = '{' . strtoupper($key) . '}';
        $content = file_get_contents($file);
        $content = str_replace($key, $data, $content);
        return file_put_contents($file, $content);
    }

}

?>