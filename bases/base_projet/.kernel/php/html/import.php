<?php
namespace Kernel\Html;
use Kernel\Configuration;
use Kernel\Path;

/**
 * Librairie gerant les imports de fichiers CSS et JS
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Librarie
 */
class Import {


    /**
     * Importe un fichier javascript
     * 
     * @param string le fichier a importer
     * @param string le type de script
     * @param string le nom de la variable a instancier
     * @param string le nom de la classe a instancier
     * @return string le code HTML
     */
    static function importScript($file, $type = 'module', $name = null, $class = null) {
        if (Configuration::get()->render->use_minifying) {
            $inf = pathinfo($file);
            $file = $inf['dirname'] . '/' . $inf['filename'] . '.min.js';
        }
        if (is_null($name) && is_null($class)) {
            $js = Builder::create('script', [
                'type' => $type,
                'src' => Path::relative($file)
            ], null, false);
        } else {
            $js = Builder::create('script', [
                'type' => $type,
            ], 
            'import ' . $class . ' from "' . Path::relative($file) . '";
            window.' . $name . ' = new ' . $class .'();');
        }
        return $js;
    }
    

    /**
     * Importe un fichier less
     * 
     * @param string le fichier a importer
     * @param string le type de ressource
     * @return string le code HTML
     */
    static function importStyle($file, $rel = 'stylesheet/less') {
        if (Configuration::get()->render->use_minifying) {
            $inf = pathinfo($file);
            $file = $inf['dirname'] . '/' . $inf['filename'] . '.min.css';
            $rel = 'stylesheet';
        }
        $css = Builder::create('link', [
            'rel' => $rel,
            'type' => 'text/css',
            'href' => Path::relative($file)
        ]);
        return $css;
    }


    /**
     * Execute du code javascript
     * 
     * @param string le code javascript
     * @param string le type de script
     * @return string le code HTML
     */
    static function runScript($script, $type = 'module') {
        $js = Builder::create('script', [
            'type' => $type,
        ], $script);
        return $js;
    }


    /**
     * Ajoute une balise style
     * 
     * @param string le code css
     * @return string le code HTML
     */
    static function addStyle($style) {
        $css = Builder::create('style', [
            'type' => 'text/css',
        ], $style);
        return $css;
    }
    
}
