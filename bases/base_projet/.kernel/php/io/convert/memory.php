<?php
namespace Kernel\Io\Convert;



/**
 * Librairie de conversion d'unité de memoire.
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
	 * @var array Unités de mémoire utiliser pour la conversion.
	 */
	private const MEMORY_UNITS = [ "o", "Ko", "Mo", "Go", "To" ];


	/**
	 * Convertir un nombre d'octets en unité de mémoire
	 * 
	 * @example convert(1024) => 1Ko
	 * @param int $bytes Le nombre d'octets à convertir.
	 * @return string La valeur convertie.
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
	 * Convertit une unite de memoire en nombre d'octets.
	 * 
	 * @example bytes(1, 'Go') => 1073741824
	 * @param int $number Le nombre à convertir.
	 * @param string $unit La unité de mémoire à convertir.
	 * @return string La valeur convertie.
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
