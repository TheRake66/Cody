<?php

namespace Librairie;



class Convert {

    /**
     * Convertit un prix en format francais
     * 
     * @param string Prix brute 1000000.50000€
	 * @return string Prix convertit 1 000 000,50€
     */
	public static function toEuro($unPrix) {
		$exp = explode('.', $unPrix);
		$tmp = '';

		$count = 1;
		for ($i = strlen($exp[0]) - 1; $i >= 0; $i--) { // 000000 -> 000 000
			$tmp = substr($exp[0], $i, 1) . $tmp;
			if ($count%3==0 && $i > 0) $tmp = ".{$tmp}";
			$count++;
		}
		
		if (count($exp) > 1) {
			$tmp .= ',';
			$f = $exp[1];
			$ln = strlen($f);
			if ($ln == 1) $tmp .= "{$f}0"; // .0 -> .00
			elseif ($ln >= 2) $tmp .= substr($exp[1], 0, 2); // .000000 -> .00
		}

		return "{$tmp}€";
	}
	
}
