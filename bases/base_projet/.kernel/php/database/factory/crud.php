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
     * Retourne tous les objets d'une table.
     * 
     * @return array Les objets de la table.
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
     * Retourne le nombre d'objets d'une table.
     * 
     * @return int Le nombre d'objets de la table.
     */
    static function size() { 
        return Toogle::object(function() {
            return Query::cell(
                'SELECT COUNT(1) ' .
                Builder::from(static::class));
        }, static::class);
    }


    /**
     * Détruit tous les objets d'une table.
     * 
     * @return bool True si la destruction a réussi.
     */
    static function truncat() { 
        return Toogle::object(function() {
            return Query::execute(
                'TRUNCATE TABLE ' . Reflection::table(static::class));
        }, static::class);
    }


    /**
     * Vérifie si un ou des objets existent dans la table.
     * 
     * @param array $where Les propriétés à utiliser pour la clause WHERE.
     * @return bool True si l'objet ou les objets existent.
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
     * Compte le nombre d'objets dans la table par rapport à une un objet.
     * 
     * @param array $where Les propriétés à utiliser pour la clause WHERE.
     * @return int Le nombre d'objets.
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
     * Crée un nouvel objet dans la table.
     * 
     * @return bool True si la création a réussi.
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
     * Récupère un objet dans la table.
     * 
     * @param array $where Les propriétés à utiliser pour la clause WHERE.
     * @return object L'objet trouvé.
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
     * Met à jour un objet dans la table.
     * 
     * @param array $where Les propriétés à utiliser pour la clause WHERE.
     * @return bool True si la mise à jour a réussi.
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
     * Supprime un objet dans une table.
     * 
     * @param array $where Les propriétés à utiliser pour la clause WHERE.
     * @return bool True si la suppression a réussi.
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
     * Récupère plusieurs objets dans la table.
     * 
     * @param array $where Les propriétés à utiliser pour la clause WHERE.
     * @return object Les objets trouvés.
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