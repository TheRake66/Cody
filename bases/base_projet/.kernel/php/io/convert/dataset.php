<?php
namespace Kernel\Io\Convert;



/**
 * Librairie de conversion de tableau.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Dataset {

	/**
     * @var int Les directions de parcours du tableau.
	 */
	const ITERATION_DIRECTION_LEFT = 0;
	const ITERATION_DIRECTION_RIGHT = 1;


	/**
	 * Vérifie si un tableau est associatif.
	 * 
	 * @example assoc(['a' => 1, 'b' => 2]) => true
	 * @example assoc([1, 2]) => false
	 * @param array $array Le tableau à vérifier.
	 * @return bool True si le tableau est associatif.
	 */
	static function assoc($array) {
		if (array() === $array) return false;
		return array_keys($array) !== range(0, count($array) - 1);
	}


	/**
	 * Renvoie la valeur du premier élément trouvé dans le tableau qui respecte
	 * la condition donnée par la fonction de test passée en argument.
	 * 
	 * @example find([1, 2, 3, 4], function($value) { 
	 * 		return $value > 2; 
	 * }) => 3
	 * @param array $array Le tableau à parcourir.
	 * @param callable $callback La fonction de test.
	 * @return mixed La valeur du premier élément trouvé.
	 */
	static function find($array, $callback) {
		foreach ($array as $element) {
			if ($callback($element)) {
				return $element;
			}
		}
	}


	/**
	 * Crée et retourne un nouveau tableau contenant tous les éléments du 
	 * tableau d'origine qui remplissent une condition déterminée par la 
	 * fonction de test passée en argument.
	 * 
	 * @example filter([1, 2, 3, 4], function($value) { 
	 * 		return $value > 2; 
	 * }) => [3, 4]
	 * @param array $array Le tableau à parcourir.
	 * @param callable $callback La fonction de test.
	 * @return array Le tableau filtré.
	 */
	static function filter($array, $callback) {
		$result = [];
		foreach ($array as $element) {
			if ($callback($element)) {
				$result[] = $element;
			}
		}
		return $result;
	}


	/**
	 * Crée un nouveau tableau avec les résultats de l'appel d'une fonction
	 * fournie sur chaque élément du tableau appelant.
	 * 
	 * @example map([1, 2, 3, 4], function($value) { 
	 * 		return $value * 2; 
	 * }) => [2, 4, 6, 8]
	 * @param array $array Le tableau à parcourir.
	 * @param callable $callback La fonction de transformation.
	 * @return array Le tableau transformé.
	 */
	static function map($array, $callback) {
		$result = [];
		foreach ($array as $element) {
			$result[] = $callback($element);
		}
		return $result;
	}


	/**
	 * Applique une fonction qui est un « accumulateur » et qui traite chaque valeur 
	 * d'une liste (dans la direction donnée) afin de la réduire à une seule valeur.
	 * 
	 * @example reduce([1, 2, 3, 4], function($acc, $value) { 
	 * 		return $acc + $value; 
	 * }, 0) => 10
	 * @param array $array Le tableau à parcourir.
	 * @param callable $callback La fonction de réduction.
	 * @param mixed $initial La valeur initiale de l'accumulateur.
	 * @param int $direction La direction de parcours du tableau.
	 */
	static function reduce($array, $callback, $initial = null, $direction = self::ITERATION_DIRECTION_RIGHT) {
		if ($direction === self::ITERATION_DIRECTION_LEFT) {
			$array = array_reverse($array);
		}
		$accumulator = $initial;
		foreach ($array as $element) {
			$accumulator = $callback($accumulator, $element);
		}
		return $accumulator;
	}


	/**
	 * Remplit tous les éléments d'un tableau entre deux index avec
	 * une valeur statique. 
	 * 
	 * @example fill([1, 2, 3, 4], 0, 1, 3) => [1, 0, 0, 4]
	 * @param array $array Le tableau à remplir.
	 * @param mixed $value La valeur à insérer.
	 * @param int $start L'index de début.
	 * @param int $end L'index de fin, si null, le tableau 
	 * est rempli jusqu'à la fin.
	 */
	static function fill($array, $value, $start = 0, $end = null) {
		if ($end === null) {
			$end = count($array);
		}
		for ($i = $start; $i < $end; $i++) {
			$array[$i] = $value;
		}
		return $array;
	}


	/**
	 * Permet de tester si tous les éléments d'un tableau vérifient une 
	 * condition donnée par une fonction en argument.
	 * 
	 * @example every([1, 2, 3, 4], function($value) { 
	 * 		return $value > 0; 
	 * }) => true
	 * @param array $array Le tableau à tester.
	 * @param callable $callback La fonction de test.
	 * @return bool True si tous les éléments vérifient la condition.
	 */
	static function every($array, $callback) {
		foreach ($array as $element) {
			if (!$callback($element)) {
				return false;
			}
		}
		return true;
	}


	/**
	 * Teste si au moins un élément du tableau passe le test implémenté 
	 * par la fonction fournie.
	 * 
	 * @example some([1, 2, 3, 4], function($value) { 
	 * 		return $value > 2; 
	 * }) => true
	 * @param array $array Le tableau à tester.
	 * @param callable $callback La fonction de test.
	 * @return bool True si au moins un élément vérifie la condition.
	 */
	static function some($array, $callback) {
		foreach ($array as $element) {
			if ($callback($element)) {
				return true;
			}
		}
		return false;
	}
	

	/**
	 * Permet de créer un nouveau tableau contenant les éléments des sous-tableaux
	 * du tableau passé en argument, qui sont concaténés récursivement pour atteindre
	 * une profondeur donnée.
	 * 
	 * @example flatten([1, [2, [3, [4]]]]) => [1, 2, 3, 4]
	 * @param array $array Le tableau à aplatir.
	 * @return array Le tableau aplatit.
	 */
	static function flat($array) {
		$result = [];
		foreach ($array as $element) {
			if (is_array($element)) {
				$result = array_merge($result, self::flat($element));
			} else {
				$result[] = $element;
			}
		}
		return $result;
	}


	/**
	 * Permet d'exécuter une fonction donnée sur chaque élément du tableau associatif.
	 * 
	 * @example forEach([
	 * 		'A' => 'a',
	 * 		'B' => 'b',
	 * 		'C' => 'c',
	 * 		'D' => 'd',
	 * ], function($value, $key) { 
	 * 		echo $key . ' : ' . $value . PHP_EOL; 
	 * }) => 
	 * A : a
	 * B : b
	 * C : c
	 * D : d
	 * @param array $array Le tableau à parcourir.
	 * @param callable $callback La fonction à exécuter.
	 * @return void
	 */
	static function foreach($array, $callback) {
		foreach ($array as $key => $value) {
			$callback($value, $key);
		}
	}


	/**
	 * Permet d'exécuter une fonction donnée sur chaque élément du tableau indexé.
	 * 
	 * @example for([ 'A', 'B', 'C', 'D' ], function($element, $index) {
	 * 		echo $index . ' : ' . $element . PHP_EOL;
	 * }) =>
	 * 0 : A
	 * 1 : B
	 * 2 : C
	 * 3 : D
	 * @param array $array Le tableau à parcourir.
	 * @param callable $callback La fonction à exécuter.
	 * @param int $direction La direction de parcours du tableau.
	 * @return void
	 */
	static function for($array, $callback, $direction = self::ITERATION_DIRECTION_RIGHT) {
		if ($direction === self::ITERATION_DIRECTION_RIGHT) {
			for ($i = 0; $i < count($array); $i++) {
				$callback($array[$i], $i);
			}
		} else {
			for ($i = count($array) - 1; $i >= 0; $i--) {
				$callback($array[$i], $i);
			}
		}
	}

}

?>