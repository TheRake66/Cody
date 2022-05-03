<?php
namespace Kernel\Html;
use Kernel\Url;



/**
 * Librairie gerant les attributs HTML
 */
class Attribute {
    
    /**
     * Ajoute un attribut HTML
     * 
     * @param string valeur de l'attribut
     * @param string nom de l'attribut
     * @return string le code HTML
     */
    static function setAttrib($value, $name = 'value') {
        return $name . '="' . str_replace('"', '\\"', $value) . '"';
    }
    

    /**
     * Ajoute des attributs HTML
     * 
     * @param array les attributs [attribut => valeur]
     * @return string le code HTML
     */
    static function setAttribs($array) {
        $_ = '';
        foreach ($array as $name => $value) {
            $_ .= self::setAttrib($value, $name);
        }
        return $_;
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
    static function setForm($method = 'GET', $isMultipart = false, $route = null, $param = [], $addback = false) {
        $action = $route !== null ? 
            Url::build($route, $param, $addback) :
            Url::current();
        $enctype = $isMultipart ? 'multipart/form-data' : '';
        return 
            self::setAttrib($action, 'action') . 
            self::setAttrib($enctype, 'enctype') . 
            self::setAttrib($method, 'method');
    }


    /**
     * Ajoute un lien href
     * 
     * @param string le lien
     * @return string le code HTML
     */
    static function setHref($link) {
        return self::setAttrib($link, 'href');
    }


    /**
     * Ajoute un id
     * 
     * @param string le lien
     * @return string le code HTML
     */
    static function setId($id) {
        return self::setAttrib($id, 'id');
    }


    /**
     * Ajoute une classe
     * 
     * @param string la classe
     * @return string le code HTML
     */
    static function setClass($class) {
        return self::setAttrib($class, 'class');
    }


    /**
     * Ajoute un ou des styles
     * 
     * @param string|array le/les style(s)
     * @return string le code HTML
     */
    static function setStyle($style) {
        if (is_array($style)) {
            $_ = '';
            foreach ($style as $s => $v) {
                $_ .= $s . ':' . $v . ';';
            }
            $style = $_;
        }
        return self::setAttrib($style, 'style');
    }


    /**
     * Ajoute une src
     * 
     * @param string la src
     * @param string le texte alternatif
     * @return string le code HTML
     */
    static function setSrc($src, $alt = null) {
        $html = self::setAttrib($src, 'src');
        if (!is_null($alt)) {
            $html .= ' ' . self::setAttrib($alt, 'alt');
        }
        return $html;
    }

}
