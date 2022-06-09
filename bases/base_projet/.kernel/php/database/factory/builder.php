<?php
namespace Kernel\Database\Factory;



/**
 * Librairie créant les parties de requêtes SQL (CRUD : Create, Read, Update, Delete).
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database\Factory
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Builder {

    /**
     * Construit l'instruction de sélecteur de colonnes.
     * 
     * @example SELECT a, b, c
     * @param object|string L'objet ou la classe DTO.
     * @return string L'instruction SELECT.
     */
    static function select($obj) {
        $col = '';
        foreach (Reflection::columns($obj) as $column) {
            $col .= Reflection::parse($column) . ', ';
        }
        $col = substr($col, 0, -2);
        $sql = 'SELECT ' . $col;
        return $sql;
    }


    /**
     * Construit l'instruction de sélecteur de table.
     * 
     * @example FROM table
     * @param object|string L'objet ou la classe DTO.
     * @return string L'instruction FROM.
     */
    static function from($obj) {
        $sql = 'FROM ' . Reflection::table($obj);
        return $sql;
    }


    /**
     * Construit l'instruction pour la clause.
     * 
     * @example [
     *    'WHERE a = ?
     *    AND b = ?
     *    AND c = ?',
     *    [ 1, 2, 3 ]
     * ]
     * @param object L'objet DTO à lier.
     * @param array Les propriétés utilisées pour la clause WHERE.
     * @return array L'instruction WHERE et les valeurs associées.
     */
    static function where($obj, $clause = null) {
        $sql = '';
        $pms = [];
        if (empty($clause)) {
            $clause = Reflection::keys($obj);
        }
        foreach ((array)$obj as $prop => $val) {
            if (!is_array($clause) && $prop == $clause ||
                is_array($clause) && in_array($prop, $clause)) {
                
                $sql .= (empty($sql) ? 'WHERE' : 'AND') . ' ' . 
                Reflection::parse($prop);
            
                if (!is_null($val)) {
                    $sql .= ' = ? ';
                    $pms[] = $val;
                } else {
                    $sql .= ' IS NULL ';
                }
                if (!is_array($clause) || count($clause) == count($pms)) {
                    break;
                }
            }
        }
        $sql = substr($sql, 0, -1);
        return [ $sql, $pms ];
    }


    /**
     * Construit l'instruction pour l'insertion.
     * 
     * @example [
     *    'INSERT INTO table (a, b, c)
     *    VALUES (?, ?, ?)',     * 
     *    [ 1, 2, 3 ]
     * ]
     * @param object L'objet DTO à lier.
     * @return array L'instruction INSERT et les valeurs associées.
     */
    static function insert($obj) {
        $col = '';
        $pmv = '';
        $pms = [];
        foreach ((array)$obj as $prop => $val) {
            $col .= Reflection::parse($prop) . ', ';
            $pmv .= '?, ';
            $pms[] = $val;
        }
        $col = substr($col, 0, -2);
        $pmv = substr($pmv, 0, -2);
        $sql = 'INSERT INTO ' . Reflection::table($obj) . ' (' . $col . ') 
                VALUES (' . $pmv . ')';
        return [ $sql, $pms ];
    }


    /**
     * Construit l'instruction pour la mise à jour.
     * 
     * @example [
     *    'UPDATE table
     *    SET a = ?, b = ?, c = ?',
     *    [ 1, 2, 3 ]
     * ]
     * @param object L'objet DTO à lier.
     * @return array L'instruction UPDATE et les valeurs associées.
     */
    static function update($obj) {
        $set = '';
        $pms = [];
        foreach ((array)$obj as $prop => $val) {
            $set .= Reflection::parse($prop) . ' = ?, ';
            $pms[] = $val;
        }
        $set = substr($set, 0, -2);
        $sql = 'UPDATE ' . Reflection::table($obj) . ' 
                SET ' . $set;
        return [ $sql, $pms ];
    }

}

?>