<?php
namespace Kernel\Html;



/**
 * Librairie gérant sortie de données HTML.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Output {

    /**
     * Ajoute du code HTML.
     * 
     * @param string|array $html Le code HTML.
     * @return void
     */
    static function add($html) {
        if (is_array($html)) {
            $html = implode('', $html);
        }
        echo $html;
    }


    /**
     * Défini un attribut HTML si un valeur est fournie dans le GET.
     * 
     * @param string $name Le nom de la clé dans le GET.
     * @param string $default La valeur par défaut.
     * @param string $key Le nom de l'attribut.
     * @return string La balise HTML.
     */
    static function get($name, $default = '', $key = 'value') {
        return Attribute::set($key, $_GET[$name] ?? $default);
    }
    

    /**
     * Défini un attribut HTML si un valeur est fournie dans le POST.
     * 
     * @param string $name Le nom de la clé dans le POST.
     * @param string $default La valeur par défaut.
     * @param string $key Le nom de l'attribut.
     * @return string La balise HTML.
     */
    static function post($name, $default = '', $key = 'value') {
        return Attribute::set($key, $_POST[$name] ?? $default);
    }
    
}
