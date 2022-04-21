<?php
namespace Kernel;
use Kernel\Error;



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
			Html::add(Html::importScript('.kernel/less@4.1.1.js'));
		}
	}

}

?>