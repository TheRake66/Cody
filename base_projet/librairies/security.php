<?php

// ####################################################################################################
class Security {

    // -------------------------------------------------------
	// Genere une suite de caractere de taille variable
	public static function genererRandom($nbLetters) {
		$randString = "";
		$charUniverse = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\\=";
		for($i = 0; $i < $nbLetters; $i++) {
		   $randString .= $charUniverse[rand(0, strlen($charUniverse) - 1)];
		}
		return $randString;
	}
    // -------------------------------------------------------
	
}
// ####################################################################################################