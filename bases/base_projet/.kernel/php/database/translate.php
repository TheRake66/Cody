<?php
namespace Kernel\Database;
use DateTime;



/**
 * Librairie gerant les traductions des donnees PHP vers SQL
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Translate {

    /**
     * Convertit un parametre en parametre SQL
     * 
     * @param mixed le parametre
     * @return mixed le parametre en SQL
     */
    static function format($param) {
        if ($param instanceof DateTime) {
            return $param->format('Y-m-d H:i:s');
        } elseif (is_bool($param)) {
            return $param ? 1 : 0;
        } else {                
            return $param;
        }
    }


    /**
     * Convertit un array de parametres en parametres SQL
     * 
     * @param array les parametres
     * @return array les parametres en SQL
     */
    static function formatMany($params) {
        $parsed = [];
        foreach ($params as $name => $value) {
            $parsed[$name] = self::format($value);
        }
        return $parsed;
    }


    /**
     * Retourne null si la valeur est vide, sinon retourne la valeur
     * 
     * @param mixed la valeur a verifier
     * @return mixed null ou la valeur
     */
    static function null($value) {
        return empty($value) ? null : $value;
    }

}

?>