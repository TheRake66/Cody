<?php
namespace Kernel;
use Kernel\Error;



// Librairie Less
class Less {

	/**
	 * Charge et lance less
	 */
	static function compileLessToCss() {
		if (!Configuration::get()->in_production) {
			echo Html::importScript('.kernel/less@4.1.1.js');
		}
	}

}

?>