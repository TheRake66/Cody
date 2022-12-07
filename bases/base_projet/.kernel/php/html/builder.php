<?php
namespace Kernel\Html;

use Kernel\Io\Convert\Image;
use Kernel\Url\Location;



/**
 * Librairie gérant les créations de balises HTML.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Builder {

    /**
     * Créer une balise HTML.
     * 
     * @param string $name Le nom de la balise.
     * @param array $attributes Les attributs de la balise (name => value).
     * @param string|array $content Le contenu de la balise.
     * @param bool $selfClose Si la balise doit être auto-fermée.
     * @return string La balise HTML.
     */
    static function create($tag, $attr = null, $content = null, $selfClose = true) {
        $_ = '<' . $tag;
        if ($attr) {
            $_ .= ' ' . Attribute::set($attr);
        }
        if ($content) {
            $_ .= '>' . (is_array($content) ? implode('', $content) : $content) . '</' . $tag . '>';
        } else {
            if ($selfClose) {
                $_ .= '/>';
            } else {
                $_ .= '></' . $tag . '>';
            }
        }
        return $_;
    }
    

    /**
     * Construit une balise HTML "a".
     * 
     * @param string $text Le texte de la balise.
     * @param string $route La route de redirection.
     * @param array $param Les paramètres de l'URL.
     * @param bool $addback Si on doit ajouter un paramètre de redirection pour retourner à la page précédente.
     * @param bool $newTab Si on doit ouvrir la page dans un nouvel onglet.
     * @return string La balise HTML.
     */
    static function href($text, $route, $param = null, $addBack = false, $newTab = false) {
        $_['href'] = Location::build($route, $param, $addBack);
        if ($newTab) {
            $_['target'] = '_blank';
        }
        return self::create('a', $_, $text);
    }


	/**
	 * Construit une balise HTML "a" pour envoyer un mail.
	 * 
	 * @param string $text Le texte de la balise.
	 * @param string $email L'adresse mail.
	 * @param string $subject Le sujet du mail.
	 * @param string $body Le corps du mail.
	 * @param string $cc Les destinataires en copie.
	 * @param string $bcc Les destinataires en copie cachée.
	 * @return string La balise HTML.
	 */
	static function mailto($text, $email, $subject = null, $body = null, $cc = null, $bcc = null) {
		$href = 'mailto:' . $email;
		$add = function($key, $value) use (&$href) {
			$href .= (strpos($href, '?') === false ? '?' : '&' ) . $key . '=' . $value;
		};
		if ($subject) {
			$add('subject', $subject);
		}
		if ($body) {
			$add('body', $body);
		}
		if ($cc) {
			$add('cc', $cc);
		}
		if ($bcc) {
			$add('bcc', $bcc);
		}
		return self::create('a', [ 'href' => $href ], $text);
	}


    /**
     * Construit une balise HTML "img" à partir d'octets.
     *
     * @param array $bin Les octets de l'image.
     * @param string $alt Le texte alternatif de l'image.
     * @param bool $lazy Si on doit déclencher le chargement de l'image au survol.
     * @param string $format Le format de l'image.
     * @return string La balise HTML.
     */
    static function b64($bin, $alt = null, $lazy = false, $format = 'png') {
        return self::img(Image::b64($bin, $format), $alt, $lazy);
    }


    /**
     * Construit une balise HTML "img".
     *
     * @param string $src L'URL de l'image.
     * @param string $alt Le texte alternatif de l'image.
     * @param bool $lazy Si on doit déclencher le chargement de l'image au survol.
     * @return string La balise HTML.
     */
    static function img($src, $alt = null, $lazy = false) {
        $_ = [ 'src' => $src ];
        if ($alt) {
            $_['alt'] = $alt;
        }
        if ($lazy) {
            $_['loading'] = 'lazy';
        }
        return self::create('img', $_);
    }

    
    /**
     * Construit une balise HTML "form".
     * 
     * @param string $content Le contenu de la balise.
     * @param string $method Le mode de transmission du formulaire.
     * @param bool $multipart Si le formulaire doit être multipart.
     * @param string $route La route de redirection.
     * @param string $param Les paramètres de l'URL (uniquement pour le méthode GET).
     * @param bool $addBack Si on doit ajouter un paramètre de redirection pour retourner à la page précédente.
     * @return string La balise HTML.
     */
    static function form($content = null, $method = 'GET', $isMultipart = false, $route = null, $param = null, $addback = false) {
        $_['method'] = $method;
        if ($isMultipart) {
            $_['enctype'] = 'multipart/form-data';
        }
        if (!is_null($route)) {
            $_['action'] = Location::build($route, $param, $addback);
        }
        return self::create('form', $_, $content, false);
    }

    
    /**
     * Créer une balise HTML "input" de type caché.
     * 
     * @param string $value La valeur de l'input.
     * @param string $id L'identifiant de l'input.
     * @param string $name Le nom de l'input.
     */
    static function hidden($value, $id, $name = null) {
        return self::create('input', [
            'type' => 'hidden',
            'value' => $value,
            'id' => $id,
            'name' => $name ?? ''
        ], null, true);
    }
    
}
