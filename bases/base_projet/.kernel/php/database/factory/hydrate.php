<?php

namespace Kernel\Database\Factory;

use Kernel\Debug\Log;



/**
 * Librairie permettant d'hydrater les données dans des objets.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @package Kernel\Database\Factory
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Hydrate {

    /**
     * Hydrate un ou plusieurs objets, avec les données d'un tableau.
     * 
     * @param array $data Les données à hydrater.
     * @param object $class La classe à hydrater.
     * @param bool $many Si les données sont une liste d'objets ou un objet unique.
     * @return object L'objet ou la liste d'objets hydratés.
     */
    static function hydrate($data, $class, $many = false) {
        $process = function($data, $class) {
            $object = new $class();
            foreach ($data as $key => $value) {

                if (!property_exists($class, $key)) {
                    $key = "_$key";
                    if (!property_exists($class, $key)) {
                        Log::warning("Attention, la propriété \"$key\" n'existe pas dans la classe \"$class\".");
                        continue;
                    }
                }

                $property = new \ReflectionProperty($object, $key);
                $property->setAccessible(true);

                if (!is_null($value) && $property->hasType()) {
                    $type = $property->getType();
                    if (!is_null($type)) {
                        $name = $type->getName();
                        
                        switch ($name) {
        
                            case 'int':
                                $value = intval($value);
                                break;
        
                            case 'float':
                                $value = floatval($value);
                                break;
        
                            case 'bool':
                                $value = boolval($value);
                                break;
        
                            case 'string':
                                $value = strval($value);
                                break;
        
                            case 'array':
                                $value = json_decode($value, true);
                                break;
        
                            case 'object':
                                $value = json_decode($value);
                                break;
                                
                            case 'DateTime':
                                $value = new \DateTime($value);
                                break;
        
                            default:
                                break;
                        }
                    }
                }

                $property->setValue($object, $value);
            }
            return $object;
        };
        
        return $many ?
            array_map($process, $data, array_fill(0, count($data), $class)) :
            $process($data, $class);
    }

}

?>