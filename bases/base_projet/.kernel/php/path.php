<?php
namespace Kernel;



/**
 * Librairie gerant les chemins des fichiers
 */
class Path {


    /**
     * Retourne le chemin absolu ou s'execute le script
     * 
     * @param string le chemin a concatener
     * @return string
     */
    static function absolute($path = '') {
        return $_SERVER['DOCUMENT_ROOT'] . self::relative($path);
    }


    /**
     * Inclut un fichier via un chemin relatif
     * 
     * @param string le chemin du fichier
     * @param bool true si le fichier doit etre inclus qu'une seule fois
     * @return void
     * @throws Error Si le fichier n'est pas accessible
     */
    static function require($path, $once = true) {
        if ($once) {
            require_once self::absolute($path);
        } else {
            require self::absolute($path);
        }
    }


    /**
     * Inclut un fichier via un chemin relatif
     * 
     * @param string le chemin du fichier
     * @param bool true si le fichier doit etre inclus qu'une seule fois
     * @return void
     */
    static function include($path, $once = true) {
        if ($once) {
            include_once self::absolute($path);
        } else {
            include self::absolute($path);
        }
    }
    
    
    /**
     * Retourne le chemin relatif ou s'execute le script par rapport au
     * chemin absulu ou s'execute le serveur
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function relative($path = '') {
        return str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . '/' . $path;
    }


    /**
     * Chemin relatif du dossier des assets
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function assets($path = '') {
        return self::relative('assets/' . $path);
    }


    /**
     * Chemin relatif du dossier des images
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function assetsImg($path = '') {
        return self::assets('img/' . $path);
    }


    /**
     * Chemin relatif du dossier des polices de caracteres
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function assetsFont($path = '') {
        return self::assets('font/' . $path);
    }


    /**
     * Chemin relatif du dossier des sons
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function assetsSound($path = '') {
        return self::assets('sound/' . $path);
    }


    /**
     * Chemin relatif du dossier des videos
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function assetsVideo($path = '') {
        return self::assets('video/' . $path);
    }

}

?>