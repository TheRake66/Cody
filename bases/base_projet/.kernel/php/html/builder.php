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
    static function createElement($tag, $attr = [], $content = null, $selfClose = true) {
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
     * @param string la route
     * @param array les parametres
     * @param bool si on ajoute le parametre de retour
     * @param bool si on ouvre la page dans une nouvelle fenetre
     * @return string le code HTML
     */
    static function buildHref($route, $param = [], $addBack = false, $newTab = false) {
        return self::createElement('a', [
                'href' => Url::build($route, $param, $addBack),
                'target' => $newTab ? '_blank' : null
            ]);
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
        return self::createElement('img', [
            'src' => Image::binToB64($bin, $format),
            'alt' => $alt
        ]);
    }
    
}
