<?php
namespace Kernel\IO\Convert;



/**
 * Librairie de conversion de donnees de type numerique
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Number {

    /**
     * Convertit un prix en format europeen
     * 
	 * @example euro(12.5) => 12,50 €
     * @param double prix brute
	 * @return string prix convertit
     */
	static function euro($num) {
		return number_format($num, 2, ',', ' ') . ' €';
	}


	/**
	 * Convertir un nombre en format occidentale
	 * 
	 * @example occident(1200000.123) => 1 200 000,123
	 * @param double le nombre
	 * @param int nombre de chiffre apres la virgule
	 * @return string la chaine formatee
	 */
	static function occident($decimal, $precision = 3) {
		return number_format($decimal, $precision, ',', ' ');
	}


	/**
	 * Verifie si un nombre est pair
	 * 
	 * @example even(12) => true
	 * @example even(13) => false
	 * @param int le nombre
	 * @return bool si il est pair
	 */
	static function even($num) {
		return $num % 2 == 0;
	}


	/**
	 * Verifie si un nombre est impair
	 * 
	 * @example odd(12) => false
	 * @example odd(13) => true
	 * @param int le nombre
	 * @return bool si il est impair
	 */
	static function odd($num) {
		return $num % 2 != 0;
	}

}
