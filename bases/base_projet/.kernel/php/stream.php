<?php
namespace Kernel;



/**
 * Librairie gerant les flux de donnees
 */
class Stream {
	

	/**
	 * Demarre un flux de donnees
	 * 
	 * @param object la fonction de traitement du flux
	 * @throws Error si l'extension ob_gzhandler n'est pas active en cas de mignification du flux
	 */
	static function start($callback = null) {
		$conf = Configuration::get();
		if ($conf->render->use_minifying) {
			ob_start(function($o) {
				return preg_replace(
					['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/'], 
					['>', '<', '\\1'], $o);
			});
		} else {
			ob_start($callback);
		}
	}
	

	/**
	 * Detruit le flux de donnees existant puis en demarre un nouveau
	 * 
	 * @param object la fonction de traitement du flux
	 * @throws Error si l'extension ob_gzhandler n'est pas active en cas de mignification du flux
	 */
	static function reset($callback = null) {
		self::destroy();
		self::start($callback);
	}


	/**
	 * Verifie si un flux de donnees existe
	 */
	static function exist() {
		return ob_get_length() !== false;
	}
	

	/**
	 * Retourne le contenu du flux de donnees
	 */
	static function get() {
		return ob_get_contents();
	}


	/**
	 * Nettoie le flux de donnees
	 */
	static function clean() {
		if (self::exist()) {
			ob_clean();
		}
	}


	/**
	 * Detruit le flux de donnees
	 */
	static function destroy() {
		if (self::exist()) {
			ob_end_clean();
		}
	}


	/**
	 * Envoie les donnees dans le flux
	 */
	static function send() {
		if (self::exist()) {
			ob_flush();
		}
	}


	/**
	 * Envoie les donnees dans le flux et ferme le flux
	 */
	static function close() {
		if (self::exist()) {
			ob_end_flush();
		}
	}
	
}
