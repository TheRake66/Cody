<?php
namespace Kernel;
use Kernel\Html\Import;
use Kernel\Html\Output;



/**
 * Librairie gerant le chargement de less
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Less {

	/**
	 * Charge et lance less
	 * 
	 * @return void
	 */
	static function compile() {
		if (!Configuration::get()->render->use_minifying) {
			Output::add(Import::importScript('.kernel/less@4.1.1.js'));
		}
	}

}

?>