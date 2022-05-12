<?php
namespace Kernel\Database\Factory;
use Kernel\Database\Toogle;
use Kernel\Database\Query;



/**
 * Librairie creant des requetes SQL (CRUD : Create, Read, Update, Delete)
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database\Factory
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Crud {
    
    /**
     * Retourne tous les objets d'une table
     * 
     * @param class classe DTO
     * @return array les objets DTO
     */
    static function all($class) {
        return Toogle::object(function() use ($class) {
            return Query::fetchObjects(
                Builder::buildSelect($class) . ' ' . 
                Builder::buildFrom($class),
                $class);
        }, $class);
    }


    /**
     * Retourne le nombre d'objets d'une table
     * 
     * @param class classe DTO
     * @return int le nombre d'objets
     */
    static function size($class) { 
        return Toogle::object(function() use ($class) {
            return Query::fetchCell(
                'SELECT COUNT(1) ' .
                Builder::buildFrom($class));
        }, $class);
    }


    /**
     * Detruit tous les objets d'une table
     * 
     * @param class classe DTO
     * @return bool si la destruction a reussi
     */
    static function truncat($class) { 
        return Toogle::object(function() use ($class) {
            return Query::execute(
                'TRUNCATE TABLE ' . Reflection::getTableName($class));
        }, $class);
    }


    /**
     * Verifie si un ou des objets existent dans la table
     * 
     * @param object l'objet DTO a lier
     * @param array les proprietes utilisees dans la clause
     * @return bool si il ou ils existent
     */
    static function exists($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return boolval(Query::fetchCell(
                'SELECT EXISTS (
                    SELECT 1 ' .
                    Builder::buildFrom($obj) . ' ' .
                    $where . '
                )',
                $params)); 
        }, $obj);   
    }


    /**
     * Compte le nombre d'objets dans la table par rapport a une clause
     * 
     * @param object l'objet DTO a lier
     * @param array les proprietes utilisees dans la clause
     * @return int le nombre d'objets
     */
    static function count($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::fetchCell(
                'SELECT COUNT(1) ' .
                Builder::buildFrom($obj) . ' ' .
                $where,
                $params); 
        }, $obj);
    }


    /**
     * Creer un objet dans une table
     * 
     * @param object l'objet a creer
     * @return bool si la creation a reussi
     */
    static function create($obj) {
        return Toogle::object(function() use ($obj) {
            [ $insert, $params ] = Builder::buildInsert($obj);
            return Query::execute(
                $insert,
                $params);
        }, $obj);
    }


    /**
     * Recupere un objet dans une table
     * 
     * @param object l'objet DTO a lier
     * @param array les proprietes utilisees pour la clause
     * @return object l'objet DTO
     */
    static function read($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::fetchObject(
                Builder::buildSelect($obj) . ' ' .
                Builder::buildFrom($obj) . ' ' .
                $where,
                $obj,
                $params);
        }, $obj);
    }


    /**
     * Met a jour un objet dans une table
     * 
     * @param object l'objet DTO a mettre a jour
     * @param array les proprietes utilisees pour la clause
     * @return bool si la mise a jour a reussi
     */
    static function update($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $update, $params1 ] = Builder::buildUpdate($obj, $clause);
            [ $where, $params2 ] = Builder::buildClause($obj, $clause);
            return Query::execute(
                $update . ' ' . $where,
                array_merge($params1, $params2));
        }, $obj);
    }


    /**
     * Supprime un objet dans une table
     * 
     * @param object objet a supprimer
     * @param array les proprietes utilisees pour la clause WHERE
     * @return bool si ca reussit
     */
    static function delete($obj, $clause = null) { 
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::execute(
                'DELETE ' . Builder::buildFrom($obj) . ' ' . $where,
                $params);
        }, $obj);
    }


    /**
     * Lis plusieurs objets dans une table
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes utilisees pour la clause WHERE
     * @return object les objets DTO
     */
    static function readMany($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::fetchObjects(
                Builder::buildSelect($obj) . ' ' .
                Builder::buildFrom($obj) . ' ' .
                $where,
                $obj,
                $params);
        }, $obj);
    }

}

?>