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
     * @param bool $absolute Si true, le chemin est déjà absolu.
     * @return bool True si le fichier existe et est lisible, false sinon.
     */
    static function loadable($file, $absolute = false) {
        if (!$absolute) {
            $file = Path::absolute($file);
        }
        return is_file($file) || is_readable($file);
    }
    
    
    /**
     * Charge le contenu d'un fichier.
     * 
     * @param string $file Le chemin du fichier à vérifier.
     * @param bool $absolute Si true, le chemin est déjà absolu.
     * @return mixed Le contenu du fichier.
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