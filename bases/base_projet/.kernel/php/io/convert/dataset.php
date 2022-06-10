<?php
namespace Kernel\Io\Convert;



/**
 * Librairie de conversion de tableau.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Dataset {

	/**
	 * Vérifie si un tableau est associatif.
	 * 
	 * @example assoc(['a' => 1, 'b' => 2]) => true
	 * @example assoc([1, 2]) => false
	 * @param array $array Le tableau à vérifier.
	 * @return bool True si le tableau est associatif.
	 */
	static function assoc($array) {
		if (array() === $array) return false;
		return array_keys($array) !== range(0, count($array) - 1);
	}

}
