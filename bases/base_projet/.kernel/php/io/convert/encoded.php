<?php
namespace Kernel\Io\Convert;



/**
 * Librairie de conversion de chaines de caractères.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Encoded {

	/**
	 * Coupe une chaine de caractères si elle est trop longue.
	 * 
	 * @example cut('Lorem ipsum dolor sit amet', 10) => Lorem ipsum...
	 * @example cut('Lorem', 10) => Lorem
	 * @param string $text Le texte à couper.
	 * @param int $max La taille maximum.
	 * @return string Le texte coupé ou non.
	 */
	static function cut($text, $max = 50) {
		return (strlen($text) > $max) ? substr($text, 0, $max) . '...' : $text;
	} 


	/**
	 * Retourne un tiret si la valeur est vide.
	 * 
	 * @example hyphen('Lorem ipsum dolor sit amet') => Lorem ipsum
	 * @example hyphen('') => -
	 * @param mixed $value La valeur à vérifier.
	 * @return string|mixed Le tiret ou la valeur.
	 */
	static function hyphen($value) {
		return $value === '' ? '-' : $value;
	}
	

    /**
     * Génère une chaine de caractères aléatoires.
     * 
	 * @example random(10) => 'a1b2c3d4e5f6g7h8i9j0'
	 * @example random(10, 'ABCD') => 'ADCBADBCDA'
     * @param int $size La taille de la chaine.
     * @param string $charset Les caractères à utiliser.
     * @return string La chaine de caractères aléatoires.
     */
	static function random($size = 32, $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
		$str = '';
		$max = strlen($charset) - 1;
		for ($i = 0; $i < $size; $i++) {
		   $str .= $charset[rand(0, $max)];
		}
		return $str;
	}


    /**
     * Retourne null si la valeur est vide, sinon retourne la valeur.
     * 
	 * @example null('Lorem ipsum dolor sit amet') => 'Lorem ipsum dolor sit amet'
	 * @example null('') => null
     * @param mixed $value La valeur à vérifier.
     * @return mixed La valeur ou null.
	 */
    static function null($value) {
        return $value === '' ? null : $value;
    }

}
