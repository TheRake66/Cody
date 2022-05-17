<?php
namespace Kernel\HTML;

use Kernel\Security\Configuration;
use Kernel\HTML\Import;
use Kernel\HTML\Output;



/**
 * Librairie gerant le chargement de less
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\HTML
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
	static function importLib() {
		if (!Configuration::get()->render->use_minifying) {
			Output::add(Import::importScript('.kernel/less@4.1.1.js'));
		}
	}

}

?>