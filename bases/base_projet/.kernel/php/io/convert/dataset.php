<?php
namespace Kernel\IO\Convert;



/**
 * Librairie de conversion de tableau
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Dataset {

	/**
	 * Verifi si un tableau est associatif
	 * 
	 * @example isAssoc(['a' => 1, 'b' => 2]) => true
	 * @example isAssoc([1, 2]) => false
	 * @param array le tableau a verifier
	 * @return bool si il est associatif
	 */
	static function isAssoc($array) {
		if (array() === $array) return false;
		return array_keys($array) !== range(0, count($array) - 1);
	}

}
