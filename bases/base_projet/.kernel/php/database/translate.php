<?php
namespace Kernel\Database;

use DateTime;



/**
 * Librairie gérant les traductions des données PHP vers SQL.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Translate {

    /**
     * Convertis un ou plusieurs paramètres en paramètre SQL.
     * 
     * @param mixed $params Les paramètres à convertir.
     * @return mixed Les paramètres convertis.
     */
    static function format($param) {
        $fn = function($p) {
            if ($p instanceof DateTime) {
                return $p->format('Y-m-d H:i:s');
            } elseif (is_bool($p)) {
                return $p ? 1 : 0;
            } else {                
                return $p;
            }
        };
        if (is_array($param)) {
            return array_map($fn, $param);
        } else {
            return $fn($param);
        }
    }

}

?>