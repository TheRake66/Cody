<?php
namespace Kernel\Database\Factory;
use Kernel\Database\Toogle;
use Kernel\Database\Query;



/**
 * Librairie creant des requetes SQL (CRUD : Create, Read, Update, Delete)
 */
class Crud {
    
    /**
     * Retourne tous les objets d'une table
     * 
     * @param class classe DTO faisant reference a la table
     * @return array les objets DTO
     */
    static function all($class) {
        return Toogle::object(function() use ($class) {
            return Query::fetchObjects('SELECT * FROM ' . Builder::getTableName($class), $class);
        }, $class);
    }


    /**
     * Compte les lignes d'une table
     * 
     * @param class classe DTO faisant reference a la table
     * @return int le nombre de ligne
     */
    static function size($class) { 
        return Toogle::object(function() use ($class) {
            return Query::fetchCell('SELECT COUNT(1) FROM ' . Builder::getTableName($class));
        }, $class);
    }


    /**
     * Vide une table
     * 
     * @param class classe DTO faisant reference a la table
     * @return bool si ca reussit
     */
    static function truncat($class) { 
        return Toogle::object(function() use ($class) {
            return Query::execute('TRUNCATE TABLE ' . Builder::getTableName($class));
        }, $class);
    }


    /**
     * Verifie si un resultat existe
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes dans la clause
     * @return bool si il existe
     */
    static function exists($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::fetchCell(
                'SELECT EXISTS (SELECT 1 FROM ' . Builder::getTableName($obj) . ' ' . $where . ')',
                $params); 
        }, get_class($obj));   
    }


    /**
     * Compte les lignes d'une table pour un objet
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes dans la clause
     * @return int le nombre de ligne
     */
    static function count($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::fetchCell(
                'SELECT COUNT(1) FROM ' . Builder::getTableName($obj) . ' ' . $where . ')',
                $params); 
        }, get_class($obj));
    }


    /**
     * Creer un objet dans une table
     * 
     * @param object objet a creer
     * @return bool si ca reussit
     */
    static function create($obj) {
        return Toogle::object(function() use ($obj) {
            [ $values, $params ] = Builder::buildInsert($obj);
            return Query::execute(
                'INSERT INTO ' . Builder::getTableName($obj) . $values, 
                $params);
        }, get_class($obj));
    }


    /**
     * Lis un objet dans une table
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes dans la clause
     * @return object les objets DTO
     */
    static function read($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::fetchObject(
                'SELECT * FROM ' . Builder::getTableName($obj) . ' ' . $where,
                get_class($obj),
                $params);
        }, get_class($obj));
    }


    /**
     * Met a jour un objet dans une table
     * 
     * @param object objet a mettre a jour
     * @param array les proprietes dans la clause
     * @return bool si ca reussit
     */
    static function update($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $values, $params1 ] = Builder::buildUpdate($obj, $clause);
            [ $where, $params2 ] = Builder::buildClause($obj, $clause);
            return Query::execute(
                'UPDATE ' . Builder::getTableName($obj) . ' ' . $values . ' ' . $where,
                array_merge($params1, $params2));
        }, get_class($obj));
    }


    /**
     * Supprime un objet dans une table
     * 
     * @param object objet a supprimer
     * @param array les proprietes dans la clause
     * @return bool si ca reussit
     */
    static function delete($obj, $clause = null) { 
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::execute(
                'DELETE FROM ' . Builder::getTableName($obj) . ' ' . $where,
                $params);
        }, get_class($obj));
    }


    /**
     * Lis plusieurs objets dans une table
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes dans la clause
     * @return object les objets DTO
     */
    static function readMany($obj, $clause = null) {
        return Toogle::object(function() use ($obj, $clause) {
            [ $where, $params ] = Builder::buildClause($obj, $clause);
            return Query::fetchObjects(
                'SELECT * FROM ' . Builder::getTableName($obj) . ' ' . $where,
                get_class($obj),
                $params);
        }, get_class($obj));
    }

}

?>