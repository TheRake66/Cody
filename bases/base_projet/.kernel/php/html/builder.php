<?php
namespace Kernel\Html;
use Kernel\Url;
use Kernel\Image;



/**
 * Librairie gerant les creation de balises HTML
 */
class Builder {

    /**
     * Cree un attribut HTML
     * 
     * @param string la balise HTML
     * @param array les attributs [attribut => valeur]
     * @param string le contenu de la balise
     * @param bool si la balise est une balise autofermante
     * @return string le code HTML
     */
    static function create($tag, $attr = [], $content = null, $selfClose = true) {
        $_ = '<' . $tag;
        if ($attr) {
            foreach ($attr as $key => $value) {
                $_ .= ' ' . $key . '="' . $value . '"';
            }
        }
        if ($content) {
            $_ .= '>' . $content . '</' . $tag . '>';
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
    static function href($text, $route, $param = [], $addBack = false, $newTab = false) {
        $_['href'] = Url::build($route, $param, $addBack);
        if ($newTab) {
            $_['target'] = '_blank';
        }
        return self::create('a', $_, $text);
    }


    /**
     * Construit une balise "img" HTML
     *
     * @param object le binaire de l'image
     * @param string le texte alt
     * @param string le format de l'image
     * @return string le code HTML
     */
    static function binImgToSrcB64($bin, $alt = null, $format = 'png') {
        return self::create('img', [
            'src' => Image::binToB64($bin, $format),
            'alt' => $alt
        ]);
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
    static function form($content = null, $method = 'GET', $isMultipart = false, $route = null, $param = [], $addback = false) {
        $_['method'] = $method;
        if ($isMultipart) {
            $_['enctype'] = 'multipart/form-data';
        }
        if (!is_null($route)) {
            $_['action'] = Url::build($route, $param, $addback);
        }
        return self::create('form', $_, $content, false);
    }
    
}
