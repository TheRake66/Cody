<?php
namespace Kernel\Database\Factory;

use Kernel\Error;



/**
 * Librairie creant la reflection des objets DTO
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database\Factory
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Reflection {

    /**
     * Retourne le nom de la base de donnee d'un objet ou d'une classe DTO
     * 
     * @param object l'objet DTO
     * @return string le nom de la base de donnee
     */
    static function database($dto) {
        $file = (new \ReflectionClass($dto))->getFileName();
        $path = dirname($file);
        $root = explode(DIRECTORY_SEPARATOR, $path);
        $database = array_pop($root);
        if ($database !== 'dto') {
            return $database;
        }
    }


    /**
     * Retourne le nom d'une table d'un objet ou d'une classe DTO
     * 
     * @param object l'objet DTO
     * @return string le nom de la classe
     */
    static function table($dto) {
        return strtolower((new \ReflectionClass($dto))->getShortName());
    }


    /**
     * Retourne les noms des colonnes d'un objet ou d'une classe DTO
     * 
     * @param object|string l'objet ou la classe DTO
     * @return array les noms
     */
    static function columns($dto) {
        $props = (new \ReflectionClass($dto))->getProperties();
        $_ = [];
        foreach ($props as $prop) {
            $_[] = self::parse($prop->getName());
        }
        return $_;
    }


    /**
     * Retourne la liste des cles primaires d'un objet ou d'une classe DTO
     * 
     * @param object|string l'objet ou la classe DTO
     * @return array les noms
     */
    static function keys($dto) {
        $props = (new \ReflectionClass($dto))->getProperties();
        $_ = [];
        foreach ($props as $prop) {
            $name = $prop->getName();
            if (substr($name, 0, 1) === '_') {
                $_[] = $name;
            }
        }
        return $_;
    }


    /**
     * Convertit une propriete primaire en nom de colonne
     * 
     * @param string le nom de la propriete
     * @return string le nom de la colonne
     */
    static function parse($primary) {
        if (substr($primary, 0, 1) === '_') {
            return substr($primary, 1);
        } else {
            return $primary;
        }
    }

}

?>