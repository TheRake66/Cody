<?php
namespace Kernel\IO\Convert;



/**
 * Librairie de conversion de chaines de caracteres
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Encoded {

	/**
	 * Coupe une chaine de caractere si elle est trop longue
	 * 
	 * @example cut('Lorem ipsum dolor sit amet', 10) => Lorem ipsum ...
	 * @example cut('Lorem', 10) => Lorem
	 * @param string la chaine a verifier
	 * @param int la taille max a couper
	 * @return string la chaine coupe ou non
	 */
	static function cut($text, $max = 50) {
		if (strlen($text) > $max) {
			return substr($text, 0, $max) . '...';
		} else {
			return $text;
		}
	} 


	/**
	 * Retourne un tiret si la valeur est vide
	 * 
	 * @example hyphen('Lorem ipsum dolor sit amet') => Lorem ipsum
	 * @example hyphen('') => -
	 * @param mixed la valeur
	 * @return string|mixed la valeur ou un tiret
	 */
	static function hyphen($value) {
		return empty($value) ? '-' : $value;
	}
	

    /**
     * Genere un une chaine de caractere aleatoire
     * 
	 * @example random(10) => 'a1b2c3d4e5f6g7h8i9j0'
	 * @example random(10, 'ABCD') => 'ADCBADBCDA'
     * @param int taille de la chaine
     * @param string le jeu de caracteres
     * @return string la chaine aleatoire
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
     * Retourne null si la valeur est vide, sinon retourne la valeur
     * 
	 * @example null('Lorem ipsum dolor sit amet') => 'Lorem ipsum dolor sit amet'
	 * @example null('') => null
     * @param mixed la valeur a verifier
     * @return mixed null ou la valeur
     */
    static function null($value) {
        return empty($value) ? null : $value;
    }

}
