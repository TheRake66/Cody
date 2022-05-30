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
            return Query::objects(
                Builder::select(static::class) . ' ' . 
                Builder::from(static::class),
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
            return Query::cell(
                'SELECT COUNT(1) ' .
                Builder::from(static::class));
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
                'TRUNCATE TABLE ' . Reflection::table(static::class));
        }, static::class);
    }


    /**
     * Verifie si un ou des objets existent dans la table
     * 
     * @param array les proprietes utilisees dans la where
     * @return bool si il ou ils existent
     */
    function exists($where = null) {
        return Toogle::object(function() use ($where) {
            [ $where, $params ] = Builder::where($this, $where);
            return boolval(Query::cell(
                'SELECT EXISTS (
                    SELECT 1 ' .
                    Builder::from($this) . ' ' .
                    $where . '
                )',
                $params)); 
        }, $this);   
    }


    /**
     * Compte le nombre d'objets dans la table par rapport a une where
     * 
     * @param array les proprietes utilisees dans la where
     * @return int le nombre d'objets
     */
    function count($where = null) {
        return Toogle::object(function() use ($where) {
            [ $where, $params ] = Builder::where($this, $where);
            return Query::cell(
                'SELECT COUNT(1) ' .
                Builder::from($this) . ' ' .
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
            [ $insert, $params ] = Builder::insert($this);
            return Query::execute(
                $insert,
                $params);
        }, $this);
    }


    /**
     * Recupere un objet dans une table
     * 
     * @param array les proprietes utilisees pour la where
     * @return object l'objet DTO
     */
    function read($where = null) {
        return Toogle::object(function() use ($where) {
            [ $where, $params ] = Builder::where($this, $where);
            return Query::object(
                Builder::select($this) . ' ' .
                Builder::from($this) . ' ' .
                $where,
                $this,
                $params);
        }, $this);
    }


    /**
     * Met a jour un objet dans une table
     * 
     * @param array les proprietes utilisees pour la where
     * @return bool si la mise a jour a reussi
     */
    function update($where = null) {
        return Toogle::object(function() use ($where) {
            [ $update, $params1 ] = Builder::update($this);
            [ $where, $params2 ] = Builder::where($this, $where);
            return Query::execute(
                $update . ' ' . $where,
                array_merge($params1, $params2));
        }, $this);
    }


    /**
     * Supprime un objet dans une table
     * 
     * @param array les proprietes utilisees pour la where WHERE
     * @return bool si ca reussit
     */
    function delete($where = null) { 
        return Toogle::object(function() use ($where) {
            [ $where, $params ] = Builder::where($this, $where);
            return Query::execute(
                'DELETE ' . Builder::from($this) . ' ' . $where,
                $params);
        }, $this);
    }


    /**
     * Lis plusieurs objets dans une table
     * 
     * @param array les proprietes utilisees pour la where WHERE
     * @return object les objets DTO
     */
    function many($where = null) {
        return Toogle::object(function() use ($where) {
            [ $where, $params ] = Builder::where($this, $where);
            return Query::objects(
                Builder::select($this) . ' ' .
                Builder::from($this) . ' ' .
                $where,
                $this,
                $params);
        }, $this);
    }

}

?>