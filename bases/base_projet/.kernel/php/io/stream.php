<?php
namespace Kernel\IO;

use Kernel\Security\Configuration;



/**
 * Librairie gérant les flux de données.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Stream {
	
	/**
	 * Démarre un flux de données.
	 * 
	 * @param function $callback La fonction de traitement du flux.
	 * @return void
	 * @throws Error Si l'extension ob_gzhandler n'est pas chargée.
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
	 * Détruit le flux de données existant puis en démarre un nouveau.
	 * 
	 * @param function $callback La fonction de traitement du flux.
	 * @return void
	 * @throws Error Si l'extension ob_gzhandler n'est pas chargée.
	 */
	static function reset($callback = null) {
		self::destroy();
		self::start($callback);
	}


	/**
	 * Vérifie si un flux de données existe.
	 * 
	 * @return bool True si un flux existe, false sinon.
	 */
	static function exist() {
		return ob_get_length() !== false;
	}
	

	/**
	 * Retourne le contenu du flux de données.
	 * 
	 * @return string Le contenu du flux.
	 */
	static function get() {
		return ob_get_contents();
	}


	/**
	 * Nettoie le flux de données.
	 * 
	 * @return void
	 */
	static function clean() {
		if (self::exist()) {
			ob_clean();
		}
	}


	/**
	 * Détruit le flux de données.
	 * 
	 * @return void
	 */
	static function destroy() {
		if (self::exist()) {
			ob_end_clean();
		}
	}


	/**
	 * Envoie les données dans le flux.
	 * 
	 * @return void
	 */
	static function send() {
		if (self::exist()) {
			ob_flush();
		}
	}


	/**
	 * Envoie les données dans le flux et ferme le flux.
	 * 
	 * @return void
	 */
	static function close() {
		if (self::exist()) {
			ob_end_flush();
		}
	}


	/**
	 * Retourne le flux généré par une fonction.
	 * 
	 * @param function $callback La fonction à exécuter.
	 * @return string Le flux généré.
	 */
	static function toogle($callback) {
        self::start();
        $callback();
        $stream = self::get();
        self::destroy();
		return $stream;
	}
	
}
