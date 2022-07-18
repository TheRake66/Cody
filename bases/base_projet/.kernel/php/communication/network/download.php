<?php
namespace Kernel\Communication\Network;



/**
 * Librairie gérant les fonctions de téléchargement.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Communication\Network
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Download {

    /**
     * Télécharge un fichier depuis l'URL spécifiée.
     * 
	 * @param string $url L'URL du fichier à télécharger.
	 * @param string $file Le chemin du fichier à télécharger.
     * @return bool True si le téléchargement a réussi, false sinon.
	 */
    static function get($url, $file) {
		$content = file_get_contents($url);
		if ($content !== false) {
			return file_put_contents($file, $content) !== false;
		} else {
			return false;
		}
    }
	
}

?>