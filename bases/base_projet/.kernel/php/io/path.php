<?php
namespace Kernel\IO;

use Kernel\Io\Environnement;



/**
 * Librairie gérant les chemins des fichiers.
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
     * Change le dossier courant vers un dossier spécifique.
     * Execute une fonction de callback puis change le dossier courant
     * vers le dossier initial.
     * 
     * @param callable $callback La fonction de callback à exécuter.
     * @param string|null $dir Le dossier à utiliser. Si null, le dossier du project actuel sera utilisé.
     * @return mixed La valeur de retour de la fonction de callback.
     */
    static function toogle($callback, $dir = null) {
        if (!$dir) {
            $dir = Environnement::root();
        }
        $cwd = getcwd();
        chdir($dir);
        $res = $callback();
        chdir($cwd);
        return $res;
    }
    

    /**
     * Retourne un chemin absolu à partir d'un chemin relatif.
     * 
     * @param string $path Le chemin relatif.
     * @return string Le chemin absolu.
     */
    static function absolute($path = '') {
        return Environnement::root() . DIRECTORY_SEPARATOR . $path;
    }
    
    
    /**
     * Retourne un chemin relatif à partir d'un chemin absolu.
     * 
     * @param string $path Le chemin relatif.
     * @return string Le chemin relatif.
     */
    static function relative($path = '') {
        return str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . '/' . $path;
    }


    /**
     * Retourne un chemin absolu aux assets à partir d'un chemin relatif.
     * 
     * @param string $path Le chemin relatif.
     * @return string Le chemin relatif.
     */
    static function assets($path = '') {
        return self::relative('assets/' . $path);
    }


    /**
     * Retourne un chemin absolu aux images d'assets à partir d'un chemin relatif.
     * 
     * @param string $path Le chemin relatif.
     * @return string Le chemin relatif.
     */
    static function img($path = '') {
        return self::assets('img/' . $path);
    }


    /**
     * Retourne un chemin absolu aux polices d'assets à partir d'un chemin relatif.
     * 
     * @param string $path Le chemin relatif.
     * @return string Le chemin relatif.
     */
    static function font($path = '') {
        return self::assets('font/' . $path);
    }


    /**
     * Retourne un chemin absolu aux sons d'assets à partir d'un chemin relatif.
     * 
     * @param string $path Le chemin relatif.
     * @return string Le chemin relatif.
     */
    static function sound($path = '') {
        return self::assets('sound/' . $path);
    }


    /**
     * Retourne un chemin absolu aux vidéos d'assets à partir d'un chemin relatif.
     * 
     * @param string $path Le chemin relatif.
     * @return string Le chemin relatif.
     */
    static function video($path = '') {
        return self::assets('video/' . $path);
    }

}

?>