<?php
namespace Kernel;
use Kernel\Html\Import;
use Kernel\Html\Output;



/**
 * Librairie gerant le chargement de less
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