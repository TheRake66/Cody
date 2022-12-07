<?php
namespace Kernel\Html;

use Kernel\Url\Location;



/**
 * Librairie gérant les attributs HTML.
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
     * Ajoute un ou plusieurs attributs HTML.
     * 
     * @param string|array $name Nom de l'attribut ou tableau de d'attributs (name => value).
     * @param string $value Valeur de l'attribut.
     * @return string Les attributs HTML.
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
     * Formate les attributs d'un formulaire.
     * 
     * @param string $method La méthode du formulaire.
     * @param bool $multipart Si le formulaire est multipart.
     * @param string $route La route de redirection.
     * @param string $params Les paramètres de la route (uniquement en méthode GET).
     * @param string $addback Si on doit ajouter un paramètre "back" pour retourner à la page précédente.
     * @return string Les attributs HTML.
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
     * Ajoute une valeur.
     * 
     * @param string $value La valeur.
     * @return string L'attribut HTML.
     */
    static function value($value) {
        return self::set('value', $value);
    }


    /**
     * Ajoute un lien href.
     * 
     * @param string $route La route de redirection.
     * @param array $param Les paramètres de l'URL.
     * @param bool $addback Si on doit ajouter un paramètre de redirection pour retourner à la page précédente.
     * @param bool $newTab Si on doit ouvrir la page dans un nouvel onglet.
     * @return string L'attribut HTML.
     */
    static function href($route, $param = null, $addBack = false, $newTab = false) {
        return self::set('href', Location::build($route, $param, $addBack)) . 
            (is_null($newTab) ? '' : self::set('target', $newTab));
    }


    /**
     * Ajoute un id.
     * 
     * @param string $id L'id.
     * @return string L'attribut HTML.
     */
    static function id($id) {
        return self::set('id', $id);
    }


    /**
     * Ajoute une classe.
     * 
     * @param string $class La classe.
     * @return string L'attribut HTML.
     */
    static function class($class) {
        return self::set('class', $class);
    }


    /**
     * Coche une case si la condition est vérifiée.
     * 
     * @param bool $condition La condition a vérifier.
     * @return string L'attribut HTML.
     */
    static function checked($condition) {
        return $condition ? 'checked' : '';
    }


    /**
     * Selectionne une valeur si la condition est vérifiée.
     * 
     * @param bool $condition La condition a vérifier.
     * @return string L'attribut HTML.
     */
    static function selected($condition) {
        return $condition ? 'selected' : '';
    }


    /**
     * Ajoute un ou des styles
     * 
     * @param string|array Le/les style(s) (attribut => valeur).
     * @return string Le ou les attributs HTML.
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
     * Ajoute une src.
     * 
     * @param string $src L'URL.
     * @param string $alt Le texte alternatif.
     * @return string L'attribut HTML.
     */
    static function src($src, $alt = null) {
        $html = self::set('src', $src);
        if (!is_null($alt)) {
            $html .= ' ' . self::set('alt', $alt);
        }
        return $html;
    }

}
