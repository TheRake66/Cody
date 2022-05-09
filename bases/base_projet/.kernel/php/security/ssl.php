<?php
namespace Kernel\Security;
use Kernel\Url;
use Kernel\Debug;
use Kernel\Configuration;



/**
 * Librairie gerant le protocole SSL (Secure Socket Layer)
 */
class SSL {

	/**
	 * Verifie si le protocole SSL est actif, sinon redirige vers le protocole HTTPS
	 * 
	 * @return void
	 */
	static function enable() {
		if (Configuration::get()->security->redirect_to_https) {
			if(self::isEnabled()) {
				Debug::log('SSL actif.', Debug::LEVEL_GOOD);
			} else {
				Debug::log('Activation du SSL...', Debug::LEVEL_PROGRESS);
				Url::location('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}
		}
	}


	/**
	 * Verifie si le protocole SSL est actif
	 * 
	 * @return bool si le protocole SSL est actif
	 */
	static function isEnabled() {
		return !($_SERVER['SERVER_PORT'] !== 443 && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off'));
	}

}

?>