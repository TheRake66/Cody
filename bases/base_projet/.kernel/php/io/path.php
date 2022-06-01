<?php
namespace Kernel\IO;



/**
 * Librairie gerant les chemins des fichiers
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Path {


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
     * Retourne le chemin relatif ou s'execute le script par rapport au
     * chemin absulu ou s'execute le serveur
     * 
     * @param string le chemin du fichier
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
    static function img($path = '') {
        return self::assets('img/' . $path);
    }


    /**
     * Chemin relatif du dossier des polices de caracteres
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function font($path = '') {
        return self::assets('font/' . $path);
    }


    /**
     * Chemin relatif du dossier des sons
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function sound($path = '') {
        return self::assets('sound/' . $path);
    }


    /**
     * Chemin relatif du dossier des videos
     * 
     * @param string le chemin a concatener
     * @return string le chemin complet
     */
    static function video($path = '') {
        return self::assets('video/' . $path);
    }

}

?>