<?php
namespace Kernel\Html;



/**
 * Librairie gerant sortie de donnees HTML
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Output {

    /**
     * Ajoute du code HTML
     * 
     * @param string|array le code HTML
     * @return void
     */
    static function add($html) {
        if (is_array($html)) {
            $html = implode('', $html);
        }
        echo $html;
    }


    /**
     * Defini un attribut HTML si un valeur est fournie dans le GET
     * 
     * @param string nom de la valeur
     * @param string valeur par defaut
     * @param string propriete HTML
     * @return string le code HTML
     */
    static function issetGET($name, $default = '', $key = 'value') {
        return Attribute::set($key, $_GET[$name] ?? $default);
    }
    

    /**
     * Defini un attribut HTML si un valeur est fournie dans le POST
     * 
     * @param string nom de la valeur
     * @param string valeur par defaut
     * @param string propriete HTML
     * @return string le code HTML
     */
    static function issetPOST($name, $default = '', $key = 'value') {
        return Attribute::set($key, $_POST[$name] ?? $default);
    }
    
}
