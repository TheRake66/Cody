<?php
namespace Kernel;
use Kernel\Error;



/**
 * Librairie gerant le chargement de less
 */
class Less {

	/**
	 * Charge et lance less
	 */
	static function compile() {
		if (!Configuration::get()->use_minifying) {
			echo Html::importScript('.kernel/less@4.1.1.js');
		}
	}

}

?>