<?php

namespace Librairie;



class Security {

	/**
	 * Verifie et active le protocole SSL
	 */
	static function activerSSL() {
		if($_SERVER['SERVER_PORT'] !== 443 &&
			(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
			header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			exit;
		}
	}

}

?>