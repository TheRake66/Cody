<?php
namespace Kernel\Io\Convert;



/**
 * Librairie de conversion de données de type numérique.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Number {

    /**
     * Convertit un prix en format Européen.
     * 
	 * @example euro(12.5) => 12,50 €
     * @param double $num Le prix à convertir.
	 * @return string Le prix au format Européen.
     */
	static function euro($num) {
		return number_format($num, 2, ',', ' ') . ' €';
	}


	/**
	 * Convertit un nombre en format occidentale.
	 * 
	 * @example occident(1200000.123) => 1 200 000,123
	 * @param double $num Le nombre à convertir.
	 * @param int $precision Le nombre de chiffres après la virgule.
	 * @return string La valeur convertie.
	 */
	static function occident($num, $precision = 3) {
		return number_format($num, $precision, ',', ' ');
	}


	/**
	 * Vérifie si un nombre est pair.
	 * 
	 * @example even(12) => true
	 * @example even(13) => false
	 * @param int $num Le nombre à vérifier.
	 * @return bool True si le nombre est pair, false sinon.
	 */
	static function even($num) {
		return $num % 2 == 0;
	}


	/**
	 * Vérifie si un nombre est impair.
	 * 
	 * @example odd(12) => false
	 * @example odd(13) => true
	 * @param int $num Le nombre à vérifier.
	 * @return bool True si le nombre est impair, false sinon.
	 */
	static function odd($num) {
		return $num % 2 != 0;
	}

}
