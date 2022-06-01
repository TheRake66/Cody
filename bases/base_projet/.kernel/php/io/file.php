<?php
namespace Kernel\IO;



/**
 * Librairie gerant les fichiers
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
     * Inclut un fichier via un chemin relatif
     * 
     * @param string le chemin du fichier
     * @param bool true si le fichier doit etre inclus qu'une seule fois
     * @return void
     * @throws Error Si le fichier n'est pas accessible
     */
    static function require($file, $once = true) {
        if ($once) {
            require_once Path::absolute($file);
        } else {
            require Path::absolute($file);
        }
    }


    /**
     * Inclut un fichier via un chemin relatif
     * 
     * @param string le chemin du fichier
     * @param bool true si le fichier doit etre inclus qu'une seule fois
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
     * Verifi si un fichier existe et est lisible
     * 
     * @param string le chemin du fichier
     * @param bool si c'est deja un chemin absolu
     * @return bool si il existe et qu'il est lisible
     */
    static function loadable($file, $absolute = false) {
        if (!$absolute) {
            $file = Path::absolute($file);
        }
        return is_file($file) || is_readable($file);
    }
    
    
    /**
     * Charge le contenu d'un fichier
     * 
     * @param string le chemin du fichier
     * @param bool si c'est deja un chemin absolu
     * @return mixed le contenu du fichier
     */
    static function load($file, $absolute = false) {
        if (!$absolute) {
            $file = Path::absolute($file);
        }
        if (is_file($file) || is_readable($file)) {
            return file_get_contents($file);
        }
    }

}

?>