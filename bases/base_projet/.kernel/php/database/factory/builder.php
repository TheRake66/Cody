<?php
namespace Kernel\Database\Factory;
use Kernel\Error;



/**
 * Librairie creant les parties de requetes SQL (CRUD : Create, Read, Update, Delete)
 */
class Builder {
    
    /**
     * Construit l'instruction pour la clause
     * 
     * WHERE a = ?
     * AND b = ?
     * AND c = ?
     * 
     * [ 1, 2, 3]
     * 
     * @param object l'objet DTO a lier
     * @param array les proprietes utilisees pour la clause WHERE
     * @return array l'instruction WHERE et les parametres
     */
    static function buildClause($obj, $clause = null) {
        $sql = '';
        $arr = [];
        if (empty($clause)) {
            $props = (new \ReflectionClass($obj))->getProperties();
            $clause = [];
            foreach ($props as $prop) {
                $name = $prop->getName();
                if (substr($name, 0, 1) === '_') {
                    $clause[] = $name;
                }
            }
            if (empty($clause)) {
                Error::trigger('Aucune clé primaire pour la classe "' . get_class($obj) . '" !');
            }
        }
        foreach ((array)$obj as $prop => $val) {
            if (!is_array($clause) && $prop == $clause ||
                is_array($clause) && in_array($prop, $clause)) {
                $sql .= (empty($sql) ? 'WHERE' : 'AND') . ' ' . self::primaryToColumn($prop);
                if (!is_null($val)) {
                    $sql .= ' = ? ';
                    $arr[] = $val;
                } else {
                    $sql .= ' IS NULL ';
                }
                if (!is_array($clause) || count($clause) == count($arr)) {
                    break;
                }
            }
        }
        $len = strlen($sql);
        if ($len > 0) {
            $sql = substr($sql, 0, $len - 1);
        }
        return [ $sql, $arr ];
    }


    /**
     * Construit l'instruction pour l'insertion
     * 
     * (a, b, c) VALUES (?, ?, ?)
     * 
     * [ 1, 2, 3 ]
     * 
     * @param object l'objet DTO a lier
     * @return array l'instruction INSERT et les parametres
     */
    static function buildInsert($obj) {
        $col = '';
        $pmv = '';
        $pms = [];
        foreach ((array)$obj as $prop => $val) {
            $col .= self::primaryToColumn($prop) . ', ';
            $pmv .= '?, ';
            $pms[] = $val;
        }
        $len = strlen($col);
        if ($len > 0) {
            $col = substr($col, 0, $len - 2);
        }
        $len2 = strlen($pmv);
        if ($len2 > 0) {
            $pmv = substr($pmv, 0, $len2 - 2);
        }
        return [ '(' . $col . ') VALUES (' . $pmv . ')', $pms ];
    }


    /**
     * Construit l'instruction pour la mise a jour
     * 
     * SET a = ?, b = ?, c = ?
     * 
     * [ 1, 2, 3 ]
     * 
     * @param object l'objet DTO a lier
     * @return array l'instruction UPDATE et les parametres
     */
    static function buildUpdate($obj) {
        $set = '';
        $col = [];
        foreach ((array)$obj as $prop => $val) {
            $set .= self::primaryToColumn($prop) . ' = ?, ';
            $col[] = $val;
        }
        $len = strlen($set);
        if ($len > 0) {
            $set = substr($set, 0, $len - 2);
        }
        return [ 'SET ' . $set, $col ];
    }


    /**
     * Retourne le nom d'une table via sa classe
     * 
     * @param object l'objet DTO
     * @return string le nom de la classe
     */
    static function getTableName($obj) {
        return strtolower((new \ReflectionClass($obj))->getShortName());
    }


    /**
     * Retourne les noms des colonnes d'une table
     * 
     * @param object l'objet DTO
     * @return array les noms
     */
    static function getColumnName($obj) {
        $props = (new \ReflectionClass($obj))->getProperties();
        $_ = [];
        foreach ($props as $prop) {
            $_[] = self::primaryToColumn($prop->getName());
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