<?php
namespace Cody\Console;



/**
 * Librairie gérant les objets du framework.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Item {

    /**
     * @var string Le nom du fichier d'information d'un projet.
     */
    const FILE_PROJECT = 'project.json';


    static function exists($type, $name) {
    }

    static function show($type) {

    }

    static function create($type, $name) {
        
    }

    static function delete($type, $name) {

    }
}

?>