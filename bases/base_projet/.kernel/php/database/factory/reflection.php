<?php
namespace Kernel\Database\Factory;
use Kernel\Error;



/**
 * Librairie creant la reflection des objets DTO
 */
class Reflection {

    /**
     * Retourne le nom de la base de donnee d'un objet ou d'une classe DTO
     * 
     * @param object l'objet DTO
     * @return string le nom de la base de donnee
     */
    static function getDatabaseName($obj) {
        $file = (new \ReflectionClass($obj))->getFileName();
        $path = dirname($file);
        $root = explode(DIRECTORY_SEPARATOR, $path);
        $database = array_pop($root);
        return $database !== 'dto' ? $database : null;
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
    static function getTableName($obj) {
        return strtolower((new \ReflectionClass($obj))->getShortName());
    }


    /**
     * Retourne les noms des colonnes d'un objet ou d'une classe DTO
     * 
     * @param object|string l'objet ou la classe DTO
     * @return array les noms
     */
    static function getColumns($obj) {
        $props = (new \ReflectionClass($obj))->getProperties();
        $_ = [];
        foreach ($props as $prop) {
            $_[] = self::primaryToColumn($prop->getName());
        }
        return $_;
    }


    /**
     * Retourne la liste des cles primaires d'un objet ou d'une classe DTO
     * 
     * @param object|string l'objet ou la classe DTO
     * @return array les noms
     */
    static function getPrimaryKeys($obj) {
        $props = (new \ReflectionClass($obj))->getProperties();
        $_ = [];
        foreach ($props as $prop) {
            if (substr($prop->getName(), 0, 1) === '_') {
                $_[] = $prop->getName();
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
    static function primaryToColumn($primary) {
        if (substr($primary, 0, 1) === '_') {
            return substr($primary, 1);
        } else {
            return $primary;
        }
    }

}

?>