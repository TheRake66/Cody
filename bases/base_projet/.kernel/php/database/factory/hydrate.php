<?php
namespace Kernel\Database\Factory;

use Kernel\Debug\Log;



/**
 * Librairie permettant d'hydrater les donnees dans des objets
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database\Factory
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Hydrate {

    /**
     * Hydrate un objet, avec les donnees d'un tableau
     * 
     * @param array les donnees
     * @param object la classe
     * @return object l'objet
     */
    static function hydrate($data, $class) {
        $obj = new $class();
        foreach ($data as $key => $value) {
            $key = Reflection::parse($key);
            if (property_exists($class, $key)) {
                $prop = new \ReflectionProperty($obj, $key);
                $prop->setAccessible(true);
                $prop->setValue($obj, $value);
            } else {
                Log::add('Attention, le champ "' . $key . '" n\'a pas de propriété dans la classe "' . $class . '"');
            }
        }
        return $obj;
    }


    /**
     * Hydrate une liste d'objets, avec les donnees d'un tableau
     * 
     * @param array les donnees
     * @param object la classe
     * @return array la liste d'objets
     */
    static function hydrateMany($data, $class) {
        $list = [];
        foreach ($data as $key => $value) {
            $list[] = self::hydrate($value, $class);
        }
        return $list;
    }

}

?>