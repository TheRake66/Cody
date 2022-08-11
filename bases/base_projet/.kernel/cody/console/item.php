<?php
namespace Cody\Console;

use Kernel\Environnement\System;
use Kernel\IO\File;

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
     * @var string Le fichier d'information d'un projet.
     */
    const FILE_PROJECT = 'project.json';


    /**
     * @var string Le fichier d'item des APIs d'un projet.
     */
    const FILE_API = 'debug/api/api.json';


    /**
     * @var string Le fichier d'item des composants d'un projet.
     */
    const FILE_COMPONENT = 'debug/app/component.json';


    /**
     * @var string Le fichier d'item des objects d'un projet.
     */
    const FILE_OBJECT = 'debug/data/object.json';


    /**
     * @var string Le fichier d'item des traits d'un projet.
     */
    const FILE_TRAIT = 'debug/data/trait.json';


    /**
     * @var string Le fichier d'item des librairies d'un projet.
     */
    const FILE_LIBRARY = 'debug/lib/library.json';

    
    /**
     * @var string Le fichier d'item des tests unitaires d'un projet.
     */
    const FILE_TEST = 'tests/test.json';


    /**
     * Décode un fichier d'informations.
     * 
     * @param string $file Le fichier d'informations.
     * @param string|null $dir Le dossier du projet. Si null, le dossier du project actuel sera utilisé.
     * @return object|bool|null Les informations du projet, null si le fichier n'existe pas.
     */
    static function decode($file, $dir = null) {
        if ($dir === null) {
            $dir = System::root();
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        if ($json = File::load($file)) {
            return (object)json_decode($json, true);
        } else {
            return false;
        }
    }
    

    /**
     * Encode un fichier d'informations.
     * 
     * @param array|object $data Les informations du projet.
     * @param string $file Le fichier d'informations.
     * @param string|null $dir Le dossier du projet. Si null, le dossier du project actuel sera utilisé.
     * @return int|boolean Taille du fichier, false si le fichier n'a pas pu être créé.
     */
    static function encode($data, $file, $dir = null) {
        if ($dir === null) {
            $dir = System::root();
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        return File::write($file, json_encode($data, JSON_PRETTY_PRINT));
    }


    /**
     * Remplace une variable dans un fichier.
     * 
     * @param string $key La variable à remplacer.
     * @param string $data La valeur à remplacer.
     * @param string $file Le fichier à modifier.
     * @param string|null $dir Le dossier du projet. Si null, le dossier du project actuel sera utilisé.
     * @return int|boolean Taille du fichier, false si le fichier n'a pas pu être créé.
     */
    static function replace($key, $data, $file, $dir = null) {
        if ($dir === null) {
            $dir = System::root();
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        $key = '{' . strtoupper($key) . '}';
        $content = File::load($file);
        $content = str_replace($key, $data, $content);
        return File::write($file, $content);
    }


    static function list($type) {
        $elements = self::decode($type);
        foreach ($elements as $key => $value) {
            # code...
        }
    }

    static function create($type, $name) {
        
    }

    static function delete($type, $name) {

    }

}

?>