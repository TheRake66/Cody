<?php
namespace Kernel\Database\Factory;

use Kernel\Error;



/**
 * Librairie créant la réflection des objets DTO.
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
     * Retourne le nom de la base de données d'un objet ou d'une classe DTO.
     * 
     * @param object|string $class L'objet ou la classe DTO.
     * @return string Le nom de la base de données.
     */
    static function database($class) {
        $file = (new \ReflectionClass($class))->getFileName();
        $path = dirname($file);
        $root = explode(DIRECTORY_SEPARATOR, $path);
        $database = array_pop($root);
        if ($database !== 'dto') {
            return $database;
        }
    }


    /**
     * Retourne le nom de la table d'un objet ou d'une classe DTO.
     * 
     * @param object|string $class L'objet ou la classe DTO.
     * @return string Le nom de la table.
     */
    static function table($class) {
        return strtolower((new \ReflectionClass($class))->getShortName());
    }


    /**
     * Retourne le nom de la colonne d'un objet ou d'une classe DTO.
     * 
     * @param object|string $class L'objet ou la classe DTO.
     * @return array Les noms de colonnes.
     */
    static function columns($class) {
        $props = (new \ReflectionClass($class))->getProperties();
        $_ = [];
        foreach ($props as $prop) {
            $_[] = self::parse($prop->getName());
        }
        return $_;
    }


    /**
     * Retourne la liste des clés primaires d'un objet ou d'une classe DTO.
     * 
     * @param object|string $class L'objet ou la classe DTO.
     * @return array Les clés primaires.
     */
    static function keys($class) {
        $props = (new \ReflectionClass($class))->getProperties();
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
     * Convertis une propriété primaire en nom de colonne
     * 
     * @param string $prop La propriété primaire.
     * @return string Le nom de la colonne.
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