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
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Crud {
    
    /**
     * Retourne tous les objets d'une table
     * 
     * @return array les objets DTO
     */
    static function all() {
        return Toogle::object(function() {
            return Query::fetchObjects(
                Builder::buildSelect(static::class) . ' ' . 
                Builder::buildFrom(static::class),
                static::class);
        }, static::class);
    }


    /**
     * Retourne le nombre d'objets d'une table
     * 
     * @return int le nombre d'objets
     */
    static function size() { 
        return Toogle::object(function() {
            return Query::fetchCell(
                'SELECT COUNT(1) ' .
                Builder::buildFrom(static::class));
        }, static::class);
    }


    /**
     * Detruit tous les objets d'une table
     * 
     * @return bool si la destruction a reussi
     */
    static function truncat() { 
        return Toogle::object(function() {
            return Query::execute(
                'TRUNCATE TABLE ' . Reflection::getTableName(static::class));
        }, static::class);
    }


    /**
     * Verifie si un ou des objets existent dans la table
     * 
     * @param array les proprietes utilisees dans la clause
     * @return bool si il ou ils existent
     */
    function exists($clause = null) {
        return Toogle::object(function() use ($clause) {
            [ $where, $params ] = Builder::buildClause($this, $clause);
            return boolval(Query::fetchCell(
                'SELECT EXISTS (
                    SELECT 1 ' .
                    Builder::buildFrom($this) . ' ' .
                    $where . '
                )',
                $params)); 
        }, $this);   
    }


    /**
     * Compte le nombre d'objets dans la table par rapport a une clause
     * 
     * @param array les proprietes utilisees dans la clause
     * @return int le nombre d'objets
     */
    function count($clause = null) {
        return Toogle::object(function() use ($clause) {
            [ $where, $params ] = Builder::buildClause($this, $clause);
            return Query::fetchCell(
                'SELECT COUNT(1) ' .
                Builder::buildFrom($this) . ' ' .
                $where,
                $params); 
        }, $this);
    }


    /**
     * Creer un objet dans une table
     * 
     * @return bool si la creation a reussi
     */
    function create() {
        return Toogle::object(function() {
            [ $insert, $params ] = Builder::buildInsert($this);
            return Query::execute(
                $insert,
                $params);
        }, $this);
    }


    /**
     * Recupere un objet dans une table
     * 
     * @param array les proprietes utilisees pour la clause
     * @return object l'objet DTO
     */
    function read($clause = null) {
        return Toogle::object(function() use ($clause) {
            [ $where, $params ] = Builder::buildClause($this, $clause);
            return Query::fetchObject(
                Builder::buildSelect($this) . ' ' .
                Builder::buildFrom($this) . ' ' .
                $where,
                $this,
                $params);
        }, $this);
    }


    /**
     * Met a jour un objet dans une table
     * 
     * @param array les proprietes utilisees pour la clause
     * @return bool si la mise a jour a reussi
     */
    function update($clause = null) {
        return Toogle::object(function() use ($clause) {
            [ $update, $params1 ] = Builder::buildUpdate($this);
            [ $where, $params2 ] = Builder::buildClause($this, $clause);
            return Query::execute(
                $update . ' ' . $where,
                array_merge($params1, $params2));
        }, $this);
    }


    /**
     * Supprime un objet dans une table
     * 
     * @param array les proprietes utilisees pour la clause WHERE
     * @return bool si ca reussit
     */
    function delete($clause = null) { 
        return Toogle::object(function() use ($clause) {
            [ $where, $params ] = Builder::buildClause($this, $clause);
            return Query::execute(
                'DELETE ' . Builder::buildFrom($this) . ' ' . $where,
                $params);
        }, $this);
    }


    /**
     * Lis plusieurs objets dans une table
     * 
     * @param array les proprietes utilisees pour la clause WHERE
     * @return object les objets DTO
     */
    function readMany($clause = null) {
        return Toogle::object(function() use ($clause) {
            [ $where, $params ] = Builder::buildClause($this, $clause);
            return Query::fetchObjects(
                Builder::buildSelect($this) . ' ' .
                Builder::buildFrom($this) . ' ' .
                $where,
                $this,
                $params);
        }, $this);
    }

}

?>