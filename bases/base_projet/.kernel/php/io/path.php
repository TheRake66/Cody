<?php
namespace Kernel\IO;



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
     * Retourne un chemin absolu à partir d'un chemin relatif.
     * 
     * @param string $path Le chemin relatif.
     * @return string Le chemin absolu.
     */
    static function absolute($path = '') {
        return $_SERVER['DOCUMENT_ROOT'] . self::relative($path);
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


	/**
	 * Combine deux chemins avec un séparateur de dossier du système.
	 * 
	 * @param string $path1 Le premier chemin.
	 * @param string $path2 Le second chemin.
	 * @return string Le chemin combiné.
	 */
	static function combine($path1, $path2) {
		return $path1 . DIRECTORY_SEPARATOR . $path2;
	}

}

?>