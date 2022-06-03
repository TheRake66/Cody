<?php
namespace Kernel\Html;

use Kernel\Security\Configuration;
use Kernel\Html\Import;
use Kernel\Html\Output;
use Kernel\Io\Path;

/**
 * Librairie gerant le chargement de less
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Less {

	/**
	 * Charge et lance less
	 * 
	 * @return void
	 */
	static function init() {
		if (!Configuration::get()->render->use_minifying) {
			Output::add(Javascript::import('.kernel/less@4.1.1.js'));
		}
	}

    
    /**
     * Importe un fichier less
     * 
     * @param string le fichier a importer
     * @param string le type de ressource
     * @return string le code HTML
     */
    static function import($file, $rel = 'stylesheet/less') {
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
     * Ajoute une balise style
     * 
     * @param string le code css
     * @return string le code HTML
     */
    static function add($style) {
        $css = Builder::create('style', [
            'type' => 'text/css',
        ], $style);
        return $css;
    }

}

?>