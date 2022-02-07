<?php
// Librairie Security
namespace Kernel;



class Security {

	/**
	 * Verifie et active le protocole SSL
	 */
	static function activerSSL() {
		if (Configuration::get()->enable_ssl) {
			if($_SERVER['SERVER_PORT'] !== 443 &&
				(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
				header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				exit;
			} else {
				Debug::log('SSL actif.');
			}
		}
	}

}

?>