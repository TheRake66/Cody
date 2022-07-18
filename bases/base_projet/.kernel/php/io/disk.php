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

}
