<?php
namespace Kernel;



/**
 * Librairie de conversion de donnees
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Convert {

	/**
	 * @var array Unites de memoire utiliser pour la conversion
	 */
	private const MEMORY_UNITS = [ "o", "Ko", "Mo", "Go", "To" ];


    /**
     * Convertit un prix en format francais
     * 
     * @param string Prix brute 1000000.50000€
	 * @return string Prix convertit 1 000 000,50€
     */
	static function toEuro($num) {
		return number_format($num, 2, ',', ' ') . ' €';
	}


	/**
	 * Coupe une chaine de caractere si elle est trop longue
	 * 
	 * @param string la chaine a verifier
	 * @param int la taille max a couper
	 * @return string la chaine coupe ou non
	 */
	static function cutTooLong($text, $size = 50) {
		if (strlen($text) > $size) {
			return substr($text, 0, $size) . '...';
		} else {
			return $text;
		}
	} 


	/**
	 * Convertir un double en format FR
	 * 
	 * @param double le nombre
	 * @param int nombre de chiffre apres la virgule
	 * @return string la chaine formatee
	 */
	static function toFrench($decimal, $precision = 3) {
		return number_format($decimal, $precision, ',', ' ');
	}


	/**
	 * Convertir un nombre en unite de memoire
	 * 
	 * @param int le nombre
	 * @return string la chaine formatee
	 */
	static function toMemory($num) {
		$count = 0;
		while ($count < count(self::MEMORY_UNITS) - 1 && round($num, 0) >= 1000) {
			$num /= 1024;
			$count++;
		}
		return number_format($num, 2, ',', ' ') . ' ' . self::MEMORY_UNITS[$count];
	}


	/**
	 * Convertir une unite de memoire en octet
	 * 
	 * @param int le nombre
	 * @param string l'unite
	 * @return string la chaine formatee
	 */
	static function toBytes($num, $unite) {
		$count = 0;
		while ($count < count(self::MEMORY_UNITS) - 1 && self::MEMORY_UNITS[$count] != $unite) {
			$num *= 1024;
			$count++;
		}
		return round($num, 0);
	}


	/**
	 * Verifie si un nombre est pair
	 * 
	 * @param int le nombre
	 * @return bool si il est pair
	 */
	static function isEven($num) {
		return $num % 2 == 0;
	}


	/**
	 * Verifie si un nombre est impair
	 * 
	 * @param int le nombre
	 * @return bool si il est impair
	 */
	static function isOdd($num) {
		return $num % 2 != 0;
	}
	

	/**
	 * Verifi si un tableau est associatif (cle => valeur)
	 * 
	 * @param array le tableau a verifier
	 * @return bool si il est associatif
	 */
	static function isAssoc($array) {
		if (array() === $array) return false;
		return array_keys($array) !== range(0, count($array) - 1);
	}


	/**
	 * Retourne un tiret si la valeur est vide
	 * 
	 * @param string la valeur
	 * @return string|mixed la valeur ou un tiret
	 */
	static function emptyToHyphen($value) {
		return empty($value) ? '-' : $value;
	}
	

    /**
     * Genere un une chaine de caractere aleatoire
     * 
     * @param int taille de la chaine
     * @param string le jeu de caracteres
     * @return string la chaine aleatoire
     */
	static function randomString($size = 32, $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
		$str = '';
		$max = strlen($charset) - 1;
		for ($i = 0; $i < $size; $i++) {
		   $str .= $charset[rand(0, $max)];
		}
		return $str;
	}

}
