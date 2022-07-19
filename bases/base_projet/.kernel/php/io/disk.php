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
	 * @param bool $dot Si on ignore les dossier commençant par un point.
	 * @param array|string $except Le ou les noms des répertoires à ignorer.
	 * @return int|null La taille du répertoire. Null si le répertoire n'existe pas.
	 */
	static function size($dir, $dot = true, $except = []) {
		if (is_dir($dir)) {
			if (!is_array($except)) {
				$except = [$except];
			}
			$total = 0;
			$entries = array_diff(scandir($dir), array('.', '..'));
			foreach ($entries as $entry) {
				$full = $dir . DIRECTORY_SEPARATOR . $entry;
				$size = false;
				if (is_dir($full)) {
					if ((!$dot || substr($entry, 0, 1) !== '.') 
						&& !in_array($entry, $except)) {
						$size = self::size($full, $dot, $except);
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
	 * @param bool $dot Si on ignore les dossier commençant par un point.
	 * @param array|string $except Le ou les noms des répertoires à ignorer.
	 * @return int|null La taille du répertoire. Null si le répertoire n'existe pas.
	 */
	static function count($dir, $dot = true, $except = []) {
		if (is_dir($dir)) {
			if (!is_array($except)) {
				$except = [$except];
			}
			$total = 0;
			$entries = array_diff(scandir($dir), array('.', '..'));
			foreach ($entries as $entry) {
				$full = $dir . DIRECTORY_SEPARATOR . $entry;
				$count = false;
				if (is_dir($full)) {
					if ((!$dot || substr($entry, 0, 1) !== '.') 
						&& !in_array($entry, $except)) {
						$count = self::count($full, $dot, $except);
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
