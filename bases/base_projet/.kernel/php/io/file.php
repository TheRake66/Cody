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
     * Inclut un fichier. Si le fichier n'existe pas, une exception est déclenchée.
     * 
     * @param string $file Le chemin du fichier à inclure.
     * @param bool $once Si true, le fichier sera inclus une seule fois.
     * @return void
     * @throws Error Si le fichier n'existe pas.
     */
    static function require($file, $once = true) {
        if (!strpos($file, ':')) {
            $file = Path::absolute($file);
        }
        if ($once) {
            require_once $file;
        } else {
            require $file;
        }
    }


    /**
     * Inclut de un fichier.
     * 
     * @param string $file Le chemin du fichier à inclure.
     * @param bool $once Si true, le fichier sera inclus une seule fois.
     * @return void
     */
    static function include($file, $once = true) {
        if (!strpos($file, ':')) {
            $file = Path::absolute($file);
        }
        if ($once) {
            include_once $file;
        } else {
            include $file;
        }
    }
    
    
    /**
     * Vérifie si un fichier existe et est lisible.
     * 
     * @param string $file Le chemin du fichier à vérifier.
     * @return bool True si le fichier existe et est lisible, false sinon.
     */
    static function loadable($file) {
        if (!strpos($file, ':')) {
            $file = Path::absolute($file);
        }
        return is_file($file) && is_readable($file);
    }
    
    
    /**
     * Charge le contenu d'un fichier.
     * 
     * @param string $file Le chemin du fichier à vérifier.
     * @return mixed|null Le contenu du fichier. Null si le fichier n'existe pas ou n'est pas lisible.
     */
    static function load($file) {
        if (!strpos($file, ':')) {
            $file = Path::absolute($file);
        }
        if (self::loadable($file, true)) {
            return file_get_contents($file);
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
        if (!strpos($file, ':')) {
            $file = Path::absolute($file);
        }
        return is_file($file) && is_writable($file);
    }


    /**
     * Sauvegarde le contenu d'un fichier.
     * 
     * @param string $file Le chemin du fichier à vérifier.
     * @param string $content Le contenu à sauvegarder.
     * @return bool|null True si le fichier a été créé, false si le fichier existe déjà, null si le fichier n'est pas modifiable.
     */
    static function write($file, $content) {
        if (!strpos($file, ':')) {
            $file = Path::absolute($file);
        }
        if (self::writable($file, true)) {
            return file_put_contents($file, $content);
        } else {
            return null;
        }
    }

}

?>