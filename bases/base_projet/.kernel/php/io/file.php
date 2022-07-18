<?php
namespace Kernel\IO;



/**
 * Librairie gérant les fichiers.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class File {

    /**
     * Inclut de un fichier via un chemin relatif. Si le fichier n'existe pas, une exception est déclenchée.
     * 
     * @param string $file Le chemin du fichier à inclure.
     * @param bool $once Si true, le fichier sera inclus une seule fois.
     * @return void
     * @throws Error Si le fichier n'existe pas.
     */
    static function require($file, $once = true) {
        if ($once) {
            require_once Path::absolute($file);
        } else {
            require Path::absolute($file);
        }
    }


    /**
     * Inclut de un fichier via un chemin relatif.
     * 
     * @param string $file Le chemin du fichier à inclure.
     * @param bool $once Si true, le fichier sera inclus une seule fois.
     * @return void
     */
    static function include($file, $once = true) {
        if ($once) {
            include_once Path::absolute($file);
        } else {
            include Path::absolute($file);
        }
    }
    
    
    /**
     * Vérifie si un fichier existe et est lisible.
     * 
     * @param string $file Le chemin du fichier à vérifier.
     * @return bool True si le fichier existe et est lisible, false sinon.
     */
    static function loadable($file) {
        $file = realpath($file);
        if ($file !== false) {
            return is_readable($file);
        } else {
            return false;
        }
    }
    
    
    /**
     * Charge le contenu d'un fichier.
     * 
     * @param string $file Le chemin du fichier à vérifier.
     * @return mixed|null Le contenu du fichier. Null si le fichier n'existe pas ou n'est pas lisible.
     */
    static function load($file) {
        if (self::loadable($file)) {
            return file_get_contents(realpath($file));
        } else {
            return null;
        }
    }

    
    /**
     * Vérifie si un fichier existe et est modifiable.
     * 
     * @param string $file Le chemin du fichier à vérifier.
     * @return bool True si le fichier existe et est modifiable, false sinon.
     */
    static function writable($file) {
        $file = realpath($file);
        if ($file !== false) {
            return is_writable($file);
        } else {
            return false;
        }
    }


    /**
     * Sauvegarde le contenu d'un fichier.
     * 
     * @param string $file Le chemin du fichier à vérifier.
     * @param string $content Le contenu à sauvegarder.
     * @return bool|null True si le fichier a été créé, false si le fichier existe déjà, null si le fichier n'est pas modifiable.
     */
    static function write($file, $content) {
        if (self::writable($file)) {
            return file_put_contents(realpath($file), $content);
        } else {
            return null;
        }
    }

}

?>