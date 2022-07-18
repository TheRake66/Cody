<?php
namespace Cody\Console;

use Kernel\Io\Environnement;
use Kernel\Io\Convert\Memory;
use Kernel\Io\Convert\Number;
use Kernel\Io\Disk;
use Kernel\IO\File;
use Kernel\Security\Configuration;

/**
 * Librairie gérant les projets du framework.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Project {

    /**
     * Vérifie si un dossier est un dossier de projet.
     * 
     * @param string $dir Le dossier à vérifier.
     * @return boolean True si le dossier est un dossier de projet, false sinon.
     */
    static function is($dir) {
        return File::loadable($dir . DIRECTORY_SEPARATOR . Item::FILE_PROJECT);
    }


    /**
     * Décode un fichier d'informations.
     * 
     * @param string $file Le fichier d'informations.
     * @param string|null $dir Le dossier du projet. Si null, le dossier du project actuel sera utilisé.
     * @return object|bool|null Les informations du projet, null si le fichier n'existe pas.
     */
    static function decode($file, $dir = null) {
        if ($dir === null) {
            $dir = Environnement::root();
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
            $dir = Environnement::root();
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
            $dir = Environnement::root();
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        $key = '{' . strtoupper($key) . '}';
        $content = File::load($file);
        $content = str_replace($key, $data, $content);
        return File::write($file, $content);
    }


    /**
     * Liste les projets du dossier courant.
     * 
     * @return void
     */
    static function list() {
        $path = rtrim(getcwd(), '/') . '/*';
        $width = Configuration::get()->console->max_width;
        $dirs = glob($path, GLOB_ONLYDIR);
        $bar = str_repeat('═', max(1, $width - 2));
        $space = function($text, $sub = 26) use ($width) {
            return str_repeat(' ', max(0, $width - $sub - strlen($text)));
        };
        $invalid = function($text, $value, 
            $valid = Output::COLOR_FORE_DEFAULT, 
            $invalid = Output::COLOR_FORE_RED, $check = '???') use ($space) {
            Output::print('║ ' . $text . ' : ');
            Output::print($value, $value !== $check ? $valid : $invalid);
            Output::printLn($space($value) . ' ║');
        };
        $count = 0;
        foreach ($dirs as $dir) {
            if (Project::is($dir)) {
                $count++;
                $decode = Project::decode(Item::FILE_PROJECT, $dir);
                if ($decode) {
                    $folder = basename($dir);
                    $name = $decode->name ?? '???';
                    $version = $decode->version ?? '???';
                    $created = $decode->created ?? null;
                    $created = $created ? date('d/m/Y H:i:s', strtotime($created)) : '???';
                    $author = $decode->author ?? '???';
                    $nombre = Disk::count($dir);
                    $nombre = $nombre ? Number::occident($nombre, 0) : '???';
                    $taille = Disk::size($dir);
                    $taille = $taille ? Memory::convert($taille) : '???';

                    Output::printLn('╔' . $bar . '╗');
                    Output::printLn('║ Projet n°' . $count . $space($count, 13) . ' ║');
                    Output::printLn('╠' . $bar . '╣');
                    $invalid('Dossier            ', $folder);
                    $invalid('Nom                ', $name, Output::COLOR_FORE_MAGENTA);
                    $invalid('Version            ', $version, Output::COLOR_FORE_YELLOW, Output::COLOR_FORE_GREEN, Program::CODY_VERSION);
                    $invalid('Créé le            ', $created);
                    $invalid('Fait par           ', $author);
                    $invalid('Nombre de fichiers ', $nombre);
                    $invalid('Taille mémoire     ', $taille);
                    Output::printLn('╚' . $bar . '╝');
                }
            }
        }
        if ($count > 0) {
            Output::print('Listage terminé. Il y a ');
            Output::print($count, Output::COLOR_FORE_GREEN);
            Output::printLn(' projet(s) dans le dossier courant.');
            Output::printLn('Les dossiers commençant par "." ont été ignorés pour les calculs. (Sauf le dossier .kernel)');
        } else {
            Output::printLn('Heuuu, il n\'y a aucun projet dans le dossier courant...');
        }
    }


    /**
     * Initialise un project.
     * 
     * @return void
     */
    static function init() {
        Output::printLn("Initialisation du projet...");

        $p_file = Item::FILE_PROJECT;
        $c_file = Configuration::FILE_CONFIGURATION;
        $project = basename(Environnement::root());
        $version = Program::CODY_VERSION;
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $user = getenv('username');

        Project::replace('PROJECT_NAME', $project, $p_file);
        Project::replace('PROJECT_VERSION', $version, $p_file);
        Output::print('Fichier : "');
        Output::print($p_file, Output::COLOR_FORE_GREEN);
        Output::printLn('" modifié.');

        Project::replace('PROJECT_CREATED', $date, $p_file);
        Project::replace('PROJECT_AUTHOR', $user, $p_file);
        Project::replace('PROJECT_NAME', $project, $c_file);
        Project::replace('PROJECT_AUTHOR', $user, $c_file);
        Output::print('Fichier : "');
        Output::print($c_file, Output::COLOR_FORE_GREEN);
        Output::printLn('" modifié.');

        Output::printLn("Projet initialisé avec succès.");
    }

}

?>