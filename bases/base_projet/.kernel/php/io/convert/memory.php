<?php
namespace Kernel\Io\Convert;



/**
 * Librairie de conversion d'unite de memoire
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Memory {

	/**
	 * @var array Unites de memoire utiliser pour la conversion
	 */
	private const MEMORY_UNITS = [ "o", "Ko", "Mo", "Go", "To" ];


	/**
	 * Convertir un nombre d'octets en unite de memoire
	 * 
	 * @example convert(1024) => 1Ko
	 * @param int le nombre
	 * @return string la chaine formatee
	 */
	static function convert($bytes) {
		$count = 0;
		while ($count < count(self::MEMORY_UNITS) - 1 && round($bytes, 0) >= 1000) {
			$bytes /= 1024;
			$count++;
		}
		return number_format($bytes, 2, ',', ' ') . ' ' . self::MEMORY_UNITS[$count];
	}


	/**
	 * Convertit une unite de memoire en nombre d'octets
	 * 
	 * @example bytes(1, 'Go') => 1073741824
	 * @param int le nombre d'octets
	 * @param string l'unite de memoire
	 * @return string la chaine formatee
	 */
	static function bytes($number, $unit) {
		$count = 0;
		while ($count < count(self::MEMORY_UNITS) - 1 && self::MEMORY_UNITS[$count] != $unit) {
			$number *= 1024;
			$count++;
		}
		return round($number, 0);
	}

}
