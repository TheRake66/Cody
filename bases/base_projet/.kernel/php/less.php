<?php
namespace Kernel;
use Kernel\Error;



// Librairie Less
class Less {

	/**
	 * Charge et lance less
	 */
	static function compileLessToCss() {
		if (!Configuration::get()->use_minifying) {
			echo Html::importScript('.kernel/less@4.1.1.js');
		}
	}

}

?>