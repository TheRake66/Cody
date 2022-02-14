<?php
namespace Kernel;
use Kernel\Error;



// Librairie Less
class Less {

	/**
	 * Charge et lance less
	 */
	static function compileLessToCss() {
		if (Configuration::get()->in_production) {
			Html::importScript('.kernel/less@4.1.1.js');
			echo '<script>
					async function load() {
						await new Promise(r => setTimeout(r, 200));
						document.getElementsByTagName("html")[0].style.opacity = 1;
					}
					document.addEventListener("DOMContentLoaded", load);
				</script>';
		}
	}

}

?>