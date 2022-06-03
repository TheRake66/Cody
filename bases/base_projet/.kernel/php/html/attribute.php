<?php
namespace Kernel\Html;

use Kernel\Url\Location;



/**
 * Librairie gerant les attributs HTML
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Attribute {
    
    /**
     * Ajoute un ou plusieurs attributs HTML
     * 
     * @param string|array nom de l'attribut
     * @param string valeur de l'attribut
     * @return string le code HTML
     */
    static function set($name, $value = null) {
        $fn = function($n, $v) {
            return $n . '="' . str_replace('"', '\\"', $v) . '"';
        };
        if (is_array($name)) {
            $_ = '';
            foreach ($name as $n => $v) {
                $_ .= $fn($n, $v) . ' ';
            }
            return trim($_);
        } else {
            return $fn($name, $value);
        }
    }
    
    
    /**
     * Formate les attributs d'un formulaire
     * 
     * @param string la methode HTTP
     * @param boolean si des fichiers sont envoyés
     * @param string la route de redirection
     * @param string les parametres de redirection (uniquement en methode POST)
     * @param string si on ajoute le parametre de retour
     * @return string le code HTML
     */
    static function form($method = 'GET', $isMultipart = false, $route = null, $param = [], $addback = false) {
        $_ = self::set('method', $method);
        if ($isMultipart) {
            $_ .= self::set('enctype', 'multipart/form-data');
        }
        if (!is_null($route)) {
            $_ .= self::set('action', Location::build($route, $param, $addback));
        }
        return $_;
    }


    /**
     * Ajoute une valeur
     * 
     * @param string la valeur
     * @return string le code HTML
     */
    static function value($value) {
        return self::set('value', $value);
    }


    /**
     * Ajoute un lien href
     * 
     * @param string le lien
     * @param string le target
     * @return string le code HTML
     */
    static function href($link, $target = null) {
        return self::set('href', $link) . 
            (is_null($target) ? '' : self::set('target', $target));
    }


    /**
     * Ajoute un id
     * 
     * @param string le lien
     * @return string le code HTML
     */
    static function id($id) {
        return self::set('id', $id);
    }


    /**
     * Ajoute une classe
     * 
     * @param string la classe
     * @return string le code HTML
     */
    static function class($class) {
        return self::set('class', $class);
    }


    /**
     * Ajoute un ou des styles
     * 
     * @param string|array le/les style(s)
     * @return string le code HTML
     */
    static function style($style) {
        if (is_array($style)) {
            $_ = '';
            foreach ($style as $s => $v) {
                $_ .= $s . ':' . $v . ';';
            }
            $style = $_;
        }
        return self::set('style', $style);
    }


    /**
     * Ajoute une src
     * 
     * @param string la src
     * @param string le texte alternatif
     * @return string le code HTML
     */
    static function src($src, $alt = null) {
        $html = self::set('src', $src);
        if (!is_null($alt)) {
            $html .= ' ' . self::set('alt', $alt);
        }
        return $html;
    }

}
