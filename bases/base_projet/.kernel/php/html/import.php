<?php
namespace Kernel\Html;
use Kernel\Configuration;



/**
 * Librairie gerant les imports de fichiers CSS et JS
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
            $js = '<script type="' . $type . '" src="' . $file . '"></script>';
        } else {
            $js = '<script type="' . $type . '">
                    import ' . $class . ' from "./' . $file . '";
                    window.' . $name . ' = new ' . $class .'();
                </script>';
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
        return '<link rel="' . $rel . '" type="text/css" href="' . $file . '">';
    }


    /**
     * Execute du code javascript
     * 
     * @param string le code javascript
     * @param string le type de script
     * @return string le code HTML
     */
    static function runScript($script, $type = 'module') {
        return '<script type="' . $type . '">' . $script . '</script>';
    }


    /**
     * Ajoute une balise style
     * 
     * @param string le code css
     * @return string le code HTML
     */
    static function addStyle($style) {
        return '<style>' . $style . '</style>';
    }
    
}
