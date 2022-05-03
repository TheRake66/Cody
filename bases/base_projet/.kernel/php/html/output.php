<?php
namespace Kernel\Html;



/**
 * Librairie gerant sortie de donnees HTML
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
     * Defini la value si un get existe
     * 
     * @param string nom du parametre
     * @param string valeur par defaut
     * @param string propriete html
     * @return string le code HTML
     */
    static function getValue($name, $default = '', $key = 'value') {
        return Attribute::setAttrib($_GET[$name] ?? $default, $key);
    }
    

    /**
     * Defini la value si un post existe
     * 
     * @param string nom du parametre
     * @param string valeur par defaut
     * @param string propriete html
     * @return string le code HTML
     */
    static function postValue($name, $default = '', $key = 'value') {
        return Attribute::setAttrib($_POST[$name] ?? $default, $key);
    }
    
}
