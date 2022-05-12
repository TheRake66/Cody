<?php
namespace Kernel;



/**
 * Librairie gerant les flux de donnees
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel
 * @category Librarie
 */
class Stream {
	

	/**
	 * Demarre un flux de donnees
	 * 
	 * @param function la fonction de traitement du flux
	 * @return void
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
	 * @param function la fonction de traitement du flux
	 * @return void
	 * @throws Error si l'extension ob_gzhandler n'est pas active en cas de mignification du flux
	 */
	static function reset($callback = null) {
		self::destroy();
		self::start($callback);
	}


	/**
	 * Verifie si un flux de donnees existe
	 * 
	 * @return void
	 */
	static function exist() {
		return ob_get_length() !== false;
	}
	

	/**
	 * Retourne le contenu du flux de donnees
	 * 
	 * @return void
	 */
	static function get() {
		return ob_get_contents();
	}


	/**
	 * Nettoie le flux de donnees
	 * 
	 * @return void
	 */
	static function clean() {
		if (self::exist()) {
			ob_clean();
		}
	}


	/**
	 * Detruit le flux de donnees
	 * 
	 * @return void
	 */
	static function destroy() {
		if (self::exist()) {
			ob_end_clean();
		}
	}


	/**
	 * Envoie les donnees dans le flux
	 * 
	 * @return void
	 */
	static function send() {
		if (self::exist()) {
			ob_flush();
		}
	}


	/**
	 * Envoie les donnees dans le flux et ferme le flux
	 * 
	 * @return void
	 */
	static function close() {
		if (self::exist()) {
			ob_end_flush();
		}
	}
	
}
