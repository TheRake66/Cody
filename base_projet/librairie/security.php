<?php

namespace Librairie;



class Security {

    /**
     * Genere une chaine aleatoire de taille n
     * 
     * @param int Taille de la chaine
     */
	public static function genererRandom($nbLetters) {
		$randString = '';
		$charUniverse = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\\=';
		for($i = 0; $i < $nbLetters; $i++) {
		   $randString .= $charUniverse[rand(0, strlen($charUniverse) - 1)];
		}
		return $randString;
	}
	
}