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
     * Hydrate un ou plusieurs objets, avec les donnees d'un tableau
     * 
     * @param array les donnees
     * @param object la classe
     * @param bool si les donnees sont une liste d'objet ou un objet unique
     * @return object l'objet
     */
    static function hydrate($data, $class, $many = false) {
        $fn = function($d, $c) {
            $o = new $c();
            foreach ($d as $k => $v) {
                if (!property_exists($c, $k)) {
                    $k = '_' . $k;
                    if (!property_exists($c, $k)) {
                        Log::add('Attention, la propriété "' . $k . '" n\'existe pas dans la classe "' . $c . '".', Log::LEVEL_WARNING);
                        continue;
                    }
                }
                $prop = new \ReflectionProperty($o, $k);
                $prop->setAccessible(true);
                $prop->setValue($o, $v);
            }
            return $o;
        };
        if ($many) {
            return array_map($fn, $data, array_fill(0, count($data), $class));
        } else {
            return $fn($data, $class);
        }
    }

}

?>