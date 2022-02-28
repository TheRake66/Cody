<?php
namespace Kernel;



// Librairie Ajax
class Ajax {

	/**
	 * Verifi si une demande Ajax a ete envoyer dans le GET
	 * 
	 * @param string nom de la demande
	 * @param object fonction anonyme a executee en cas de demande
	 */
	static function askGET($name, $fn) {
        if (isset($_GET[$name])) {
			ob_end_clean();
			$res = $fn();
			if (!is_object($res)) {
				echo json_encode($res);
			}
            exit;
        }
	}


	/**
	 * Verifi si une demande Ajax a ete envoyer dans le POST
	 * 
	 * @param string nom de la demande
	 * @param object fonction anonyme a executee en cas de demande
	 */
	static function askPOST($name, $fn) {
        if (isset($_POST[$name])) {
			ob_end_clean();
			$res = $fn();
			if (!is_object($res)) {
				echo json_encode($res);
			}
            exit;
        }
	}
	
}
