<?php
namespace Kernel\Html;

use Kernel\Html\Import;
use Kernel\Io\Path;
use Kernel\Security\Configuration;

/**
 * Librairie gérant la communication avec Javascript.
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
     * Importe un fichier.
     * 
     * @param string $file Le chemin relatif du fichier.
     * @param string $type Le type de fichier.
     * @param string $name Le nom de la variable à instancier en cas de composant.
     * @param string $class La nom de la classe à instancier en cas de composant.
     * @param string $uuid L'identifiant unique en cas de composant.
     * @return string La balise HTML.
     */
    static function import($file, $type = 'module', $name = null, $class = null, $uuid = null) {
        if (Configuration::get()->render->use_minifying) {
            $inf = pathinfo($file);
            $file = $inf['dirname'] . '/' . $inf['filename'] . '.min.js';
        }
        $rel = Path::relative($file);
        if (is_null($name) && is_null($class) && is_null($uuid)) {
            $js = Builder::create('script', [
                'type' => $type,
                'src' => $rel
            ], null, false);
        } else {
            $js = Builder::create('script', [
                    'type' => $type,
                ], 
                "import $class from '$rel';

                let _ = new $class('$uuid');

                if (window.components === undefined) {
                    window.components = {};
                }
                
                if (window.components.$name === undefined) {
                    window.components.$name = _;
                } else if (window.components.$name instanceof Array) {
                    window.components.$name.push(_);
                } else {
                    window.components.$name = [window.components.$name, _];
                }"
            );
        }
        return $js;
    }


    /**
     * Exécute du code.
     * 
     * @param string $script Le code.
     * @param string $type Le type de fichier.
     * @return string La balise HTML.
     */
    static function run($script, $type = 'module') {
        $js = Builder::create('script', [
            'type' => $type,
        ], $script);
        return $js;
    }


    /**
     * Envoie une alerte.
     * 
     * @param string $message Le message.
     * @return string La balise HTML.
     */
    static function alert($message) {
        return self::run('alert("' . str_replace('"', '\\"', $message) . '")');
    }

    
    /**
     * Envoie une confirmation.
     * 
     * @param string $message Le message.
     * @param string $yes Le code à exécuter si oui.
     * @param string $no Le code à exécuter si non.
     * @return string La balise HTML.
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
     * Envoie une log.
     * 
     * @param string $message Le message.
     * @param string $style Le style de log.
     * @param string $type Le type de log.
     * @return string La balise HTML.
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
     * Envoie une erreur.
     *
     * @param string $message Le message.
     * @param string $style Le style de log.
     * @return string La balise HTML.
     */
    static function error($message, $style = null) {
        return self::log($message, $style, 'error');
    }


    /**
     * Envoie une information.
     *
     * @param string $message Le message.
     * @param string $style Le style de log.
     * @return string La balise HTML.
     */
    static function info($message, $style = null) {
        return self::log($message, $style, 'info');
    }
    
}
