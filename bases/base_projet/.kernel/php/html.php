<?php
namespace Kernel;
use Kernel\Url;
use Kernel\Configuration;



// Librairie Html
class Html {

    /**
     * Ouvre une balise HTML et ecris l'entete
     */
    static function begin() {
        echo '<!DOCTYPE html>
            <html lang="' . Configuration::get()->region->main_lang . '" style="opacity: 0;">
                <head>
                    <meta charset="' . Configuration::get()->website_head->charset . '">
                    <meta name="description" content="' . Configuration::get()->website_head->description . '">
                    <meta name="keywords" content="' . Configuration::get()->website_head->keywords . '">
                    <meta name="author" content="' . Configuration::get()->website_head->author . '">
                    <meta name="viewport" content="' . Configuration::get()->website_head->viewport . '">
                    <meta name="theme-color" content="' . Configuration::get()->website_head->theme_color . '">
                    <meta name="msapplication-navbutton-color" content="' . Configuration::get()->website_head->theme_color . '">
                    <meta name="apple-mobile-web-app-status-bar-style" content="' . Configuration::get()->website_head->theme_color . '">
                    <title>' . Configuration::get()->website_head->title . '</title>
                    <link rel="icon" href="favicon.ico"/>
                </head>';
    
        echo Html::importStyle('debug/app/global.less');
        echo Html::importScript('debug/app/global_brefore.js');
    }


    /**
     * Ferme la balise HTML
     */
    static function end() {
        echo Html::importScript('debug/app/global_after.js');
        echo '<script>
                async function loaded() {
                    await new Promise(r => setTimeout(r, 200));
                    document.getElementsByTagName(\'html\')[0].style.opacity = 1;
                }
                window.addEventListener("DOMContentLoaded", (event) => {
                    loaded();
                });
            </script>

        </html>';
    }
    

    /**
     * Ajoute un attribut HTML
     * 
     * @param string valeur de l'attribut
     * @param string nom de l'attribut
     * @return string l'attribut formatte
     */
    static function setAttrib($value, $name = 'value') {
        return $name . '="' . str_replace('"', '\\"', $value) . '"';
    }


    /**
     * Ajoute un lien href
     * 
     * @param string le lien
     * @return string l'attribut formatte
     */
    static function setHref($link) {
        return Html::setAttrib($link, 'href');
    }


    /**
     * Ajoute un id
     * 
     * @param string le lien
     * @return string l'attribut formatte
     */
    static function setId($id) {
        return Html::setAttrib($id, 'id');
    }


    /**
     * Ajoute une classe
     * 
     * @param string la classe
     * @return string l'attribut formatte
     */
    static function setClass($class) {
        return Html::setAttrib($class, 'class');
    }


    /**
     * Ajoute une src
     * 
     * @param string la src
     * @param string le texte alternatif
     * @return string l'attribut formatte
     */
    static function setSrc($src, $alt = null) {
        $html = Html::setAttrib($src, 'src');
        if (!is_null($alt)) {
            $html .= Html::setAttrib($alt, 'alt');
        }
        return $html;
    }


    /**
     * Construit et ajoute un lien href
     * 
     * @param string la route
     * @param array les param
     * @param string le back
     * @return string l'attribut formatte
     */
    static function buildHref($route, $param = [], $addback = false) {
        return Html::setHref(Url::build($route, $param, $addback));
    }
    

    /**
     * Defini la value si un get existe
     * 
     * @param string nom du parametre
     * @param string valeur par defaut
     * @param string propriete html
     * @return string la valeur
     */
    static function getValue($name, $default = '', $key = 'value') {
        return self::setAttrib($_GET[$name] ?? $default, $key);
    }
    

    /**
     * Defini la value si un post existe
     * 
     * @param string nom du parametre
     * @param string valeur par defaut
     * @param string propriete html
     * @return string la valeur
     */
    static function postValue($name, $default = '', $key = 'value') {
        return self::setAttrib($_POST[$name] ?? $default, $key);
    }
    

    /**
     * Importe un fichier javascript
     * 
     * @param string le fichier a importer
     * @param string le nom de la variable a instancier
     * @param string le nom de la classe a instancier
     * @return string le code HTML qui importe le script
     */
    static function importScript($file, $name = null, $class = null) {
        if (Configuration::get()->in_production) {
            $inf = pathinfo($file);
            $file = $inf['dirname'] . '/' . $inf['filename'] . '.min.js';
        }
        $js = '<script type="text/javascript" src="' . $file . '"></script>';
        if (!is_null($name) && !is_null($class)) {
            $js .= '<script>const ' . $name . ' = new ' . $class .'();</script>';
        }
        return $js;
    }
    

    /**
     * Importe un fichier less
     * 
     * @param string le fichier a importer
     * @param string le type de ressource
     * @return string le code HTML qui importe le style
     */
    static function importStyle($file, $rel = 'stylesheet/less') {
        if (Configuration::get()->in_production) {
            $inf = pathinfo($file);
            $file = $inf['dirname'] . '/' . $inf['filename'] . '.min.css';
            $rel = 'stylesheet';
        }
        return '<link rel="' . $rel . '" type="text/css" href="' . $file . '">';
    }
    
}
