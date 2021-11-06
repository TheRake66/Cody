<?php

namespace Librairie;



class Url {

	/**
	 * Remplace un parametre de l'url
	 * 
	 * @param string le nom du parametre
	 * @param string sa nouvelle valeur
	 * @return string le nouvel url
	 */
	public static function changeGet($param, $remplace) {
		$query = $_GET;
		$query[$param] = $remplace;
		return $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
	}
	
}

?>