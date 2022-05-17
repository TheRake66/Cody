<?php
namespace Kernel\HTML;

use Kernel\IO\Convert\Image;
use Kernel\URL\Location;



/**
 * Librairie gerant les creation de balises HTML
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\HTML
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Builder {

    /**
     * Cree un attribut HTML
     * 
     * @param string la balise HTML
     * @param array les attributs [attribut => valeur]
     * @param string|array le contenu de la balise
     * @param bool si la balise est une balise autofermante
     * @return string le code HTML
     */
    static function createElement($tag, $attr = null, $content = null, $selfClose = true) {
        $_ = '<' . $tag;
        if ($attr) {
            foreach ($attr as $key => $value) {
                $_ .= ' ' . $key . '="' . $value . '"';
            }
        }
        if ($content) {
            $_ .= '>' . (is_array($content) ? implode('', $content) : $content) . '</' . $tag . '>';
        } else {
            if ($selfClose) {
                $_ .= ' />';
            } else {
                $_ .= '></' . $tag . '>';
            }
        }
        return $_;
    }
    

    /**
     * Construit une balise "a" HTML
     * 
     * @param string le texte de la balise
     * @param string la route
     * @param array les parametres
     * @param bool si on ajoute le parametre de retour
     * @param bool si on ouvre la page dans une nouvelle fenetre
     * @return string le code HTML
     */
    static function buildHref($text, $route, $param = null, $addBack = false, $newTab = false) {
        $_['href'] = Location::build($route, $param, $addBack);
        if ($newTab) {
            $_['target'] = '_blank';
        }
        return self::createElement('a', $_, $text);
    }


    /**
     * Construit une balise "img" HTML a partir de donnees binaires
     *
     * @param object le binaire de l'image
     * @param string le texte alt
     * @param string le format de l'image
     * @return string le code HTML
     */
    static function buildImgBin($bin, $alt = null, $format = 'png') {
        return self::buildImg(Image::binToB64($bin, $format), $alt);
    }


    /**
     * Construit une balise "img" HTML
     *
     * @param object la source de l'image
     * @param string le texte alt
     * @param string le format de l'image
     * @return string le code HTML
     */
    static function buildImg($src, $alt = null) {
        $_ = [ 'src' => $src ];
        if ($alt) {
            $_['alt'] = $alt;
        }
        return self::createElement('img', $_);
    }

    
    /**
     * Construit une balise "form" HTML
     * 
     * @param string le contenu de la balise
     * @param string la methode HTTP
     * @param boolean si des fichiers sont envoyés
     * @param string la route de redirection
     * @param string les parametres de redirection (uniquement en methode POST)
     * @param string si on ajoute le parametre de retour
     * @return string le code HTML
     */
    static function buildForm($content = null, $method = 'GET', $isMultipart = false, $route = null, $param = null, $addback = false) {
        $_['method'] = $method;
        if ($isMultipart) {
            $_['enctype'] = 'multipart/form-data';
        }
        if (!is_null($route)) {
            $_['action'] = Location::build($route, $param, $addback);
        }
        return self::createElement('form', $_, $content, false);
    }
    
}
