<?php
namespace Kernel\Database\Factory;
use Kernel\Debug;



/**
 * Librairie permettant d'hydrater les donnees dans des objets
 */
class Hydrate {

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
            if (!self::setProperty($obj, $key, $value) &&
                !self::setProperty($obj, '_' . $key, $value)) {
                Debug::log('Attention, le champ "' . $key . '" n\'a pas de propriete dans la classe "' . $class . '"');
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


    /**
     * Change la valeur d'une propriete d'un objet quelque soit l'accessibilite
     * 
     * @param object l'objet
     * @param string la propriete
     * @param mixed la valeur
     * @return void
     */
    static function setProperty($obj, $key, $value) {
        if (property_exists($obj, $key)) {
            $prop = new \ReflectionProperty($obj, $key);
            $prop->setAccessible(true);
            $prop->setValue($obj, $value);
            return true;
        } else {
            return false;
        }
    }

}

?>