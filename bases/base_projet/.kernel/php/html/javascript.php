<?php
namespace Kernel\Html;

use Kernel\Html\Import;
use Kernel\Io\Path;
use Kernel\Security\Configuration;

/**
 * Librairie gerant la communication avec Javascript
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Javascript {

    /**
     * Importe un fichier javascript
     * 
     * @param string le fichier a importer
     * @param string le type de script
     * @param string le nom de la variable a instancier
     * @param string le nom de la classe a instancier
     * @return string le code HTML
     */
    static function import($file, $type = 'module', $name = null, $class = null) {
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
     * Execute du code javascript
     * 
     * @param string le code javascript
     * @param string le type de script
     * @return string le code HTML
     */
    static function run($script, $type = 'module') {
        $js = Builder::create('script', [
            'type' => $type,
        ], $script);
        return $js;
    }


    /**
     * Envoi une alerte javascript
     * 
     * @param string le message
     * @return string le code HTML
     */
    static function alert($message) {
        return self::run('alert("' . str_replace('"', '\\"', $message) . '")');
    }

    
    /**
     * Envoi une confirmation javascript
     * 
     * @param string le message
     * @param string le code javascript à executer si oui
     * @param string le code javascript à executer si non
     * @return string le code HTML
     */
    static function confirm($message, $yes = null, $no = null) {
        return self::run('
            if (confirm("' . str_replace('"', '\\"', $message) . '")) {
                ' . ($yes ? $yes : '') . '
            } else {
                ' . ($no ? $no : '') . '
            }');
    }


    /**
     * Envoi une log javascript
     * 
     * @param string le message
     * @param string le style
     * @param string le type de log
     * @return string le code HTML
     */
    static function log($message, $style = null, $type = 'log') {
        $message = str_replace('"', '\\"', $message);
        if (is_null($style)) {
            return self::run('console.log("' . $message . '")');
        } else {
            if (is_array($style)) {
                $_ = '';
                foreach ($style as $s => $v) {
                    $_ .= $s . ':' . $v . ';';
                }
                $style = $_;
            }
            return self::run('console.' . $type . '("%c' . $message . '", "' . $style . '")');
        }
    }


    /**
     * Envoi une erreur javascript
     *
     * @param string le message
     * @param string le style
     * @return string le code HTML
     */
    static function error($message, $style = null) {
        return self::log($message, $style, 'error');
    }


    /**
     * Envoi une information javascript
     * 
     * @param string le message
     * @param string le style
     * @return string le code HTML
     */
    static function info($message, $style = null) {
        return self::log($message, $style, 'info');
    }
    
}
