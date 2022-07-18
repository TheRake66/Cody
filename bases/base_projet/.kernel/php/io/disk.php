<?php
namespace Kernel\Io;



/**
 * Librairie de gestion des disques.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Disk {

	/**
	 * Calcul la taille d'un répertoire et ses sous-répertoires.
	 * 
	 * @param string $dir Le chemin du répertoire.
	 * @return int|null La taille du répertoire. Null si le répertoire n'existe pas.
	 */
	static function size($dir) {
		if (is_dir($dir)) {
			$total = 0;
			$entries = array_diff(scandir($dir), array('.', '..'));
			foreach ($entries as $entry) {
				$full = $dir . DIRECTORY_SEPARATOR . $entry;
				$size = false;
				if (is_dir($entry)) {
					if (substr($entry, 0, 1) !== '.' || $entry === '.kernel') {
						$size = self::size($full);
					}
				} else {
					$size = filesize($full);
				}
				if ($size !== false) {
					$total += $size;
				}
			}
			return $total;
		} else {
			return null;
		}
	}


	/**
	 * Calcul le nombre d'élément d'un répertoire et ses sous-répertoires.
	 * 
	 * @param string $dir Le chemin du répertoire.
	 * @return int|null La taille du répertoire. Null si le répertoire n'existe pas.
	 */
	static function count($dir) {
		if (is_dir($dir)) {
			$total = 0;
			$entries = array_diff(scandir($dir), array('.', '..'));
			foreach ($entries as $entry) {
				$full = $dir . DIRECTORY_SEPARATOR . $entry;
				$count = false;
				if (is_dir($entry)) {
					if (substr($entry, 0, 1) !== '.' || $entry === '.kernel') {
						$count = self::count($full);
					}
				} else {
					$count = 1;
				}
				if ($count !== false) {
					$total += $count;
				}
			}
			return $total;
		} else {
			return null;
		}
	}

}
