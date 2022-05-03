<?php
namespace Kernel\Security;
use Kernel\Url;
use Kernel\Debug;
use Kernel\Configuration;



/**
 * Librairie gerant les protocoles de securite
 */
class Protocol {

	/**
	 * Verifie et active le protocole SSL
	 * 
	 * @return void
	 */
	static function enableSsl() {
		if (Configuration::get()->security->redirect_to_https) {
			if($_SERVER['SERVER_PORT'] !== 443 &&
				(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
				Debug::log('Activation du SSL...', Debug::LEVEL_PROGRESS);
				Url::location('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			} else {
				Debug::log('SSL actif.', Debug::LEVEL_GOOD);
			}
		}
	}
}

?>