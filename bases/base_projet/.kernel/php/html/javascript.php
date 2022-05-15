<?php
namespace Kernel\HTML;

use Kernel\HTML\Import;



/**
 * Librairie gerant la communication avec Javascript
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\HTML
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Javascript {

    /**
     * Envoi une alerte javascript
     * 
     * @param string le message
     * @return string le code HTML
     */
    static function sendAlert($message) {
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
    static function sendConfirm($message, $yes = null, $no = null) {
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
    static function sendLog($message, $style = null) {
        return Import::runScript('console.log("' . str_replace('"', '\\"', $message) . '", "' . $style . '")');
    }


    /**
     * Envoi une erreur javascript
     *
     * @param string le message
     * @param string le style
     * @return string le code HTML
     */
    static function sendError($message, $style = null) {
        return Import::runScript('console.error("' . str_replace('"', '\\"', $message) . '", "' . $style . '")');
    }


    /**
     * Envoi une information javascript
     * 
     * @param string le message
     * @param string le style
     * @return string le code HTML
     */
    static function sendInfo($message, $style = null) {
        return Import::runScript('console.info("' . str_replace('"', '\\"', $message) . '", "' . $style . '")');
    }
    
}
