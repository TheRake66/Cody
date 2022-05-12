<?php
namespace Kernel;
use Kernel\Html\Import;



/**
 * Librairie gerant la communication avec Javascript
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel
 * @category Librarie
 */
class Javascript {

    /**
     * Envoi une alerte javascript
     * 
     * @param string le message
     * @return string le code HTML
     */
    static function alert($message) {
        return Import::runScript('alert("' . str_replace('"', '\\"', $message) . '")');
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
        return Import::runScript('
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
     * @return string le code HTML
     */
    static function log($message, $style = null) {
        return Import::runScript('console.log("' . str_replace('"', '\\"', $message) . '", "' . $style . '")');
    }


    /**
     * Envoi une erreur javascript
     *
     * @param string le message
     * @param string le style
     * @return string le code HTML
     */
    static function error($message, $style = null) {
        return Import::runScript('console.error("' . str_replace('"', '\\"', $message) . '", "' . $style . '")');
    }


    /**
     * Envoi une information javascript
     * 
     * @param string le message
     * @param string le style
     * @return string le code HTML
     */
    static function info($message, $style = null) {
        return Import::runScript('console.info("' . str_replace('"', '\\"', $message) . '", "' . $style . '")');
    }
    
}
