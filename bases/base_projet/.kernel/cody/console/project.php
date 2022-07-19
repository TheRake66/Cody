<?php
namespace Cody\Console;

use Kernel\Io\Convert\Memory;
use Kernel\Io\Convert\Number;
use Kernel\Io\Disk;
use Kernel\IO\File;
use Kernel\Environnement\Configuration;
use Kernel\Environnement\System;



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
     * @param string|null $dir Le dossier à vérifier. Si null, le dossier courant est utilisé.
     * @return boolean True si le dossier est un dossier de projet, false sinon.
     */
    static function is($dir = null) {
        if (is_null($dir))  {
            $dir = getcwd();
        }
        return File::loadable($dir . DIRECTORY_SEPARATOR . Item::FILE_PROJECT);
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
                $decode = Item::decode(Item::FILE_PROJECT, $dir);
                if ($decode) {
                    $folder = basename($dir);
                    $name = $decode->name ?? '???';
                    $version = $decode->version ?? '???';
                    $created = $decode->created ?? null;
                    $created = $created ? date('d/m/Y H:i:s', strtotime($created)) : '???';
                    $author = $decode->author ?? '???';
                    $nombre = Disk::count($dir, true, '.kernel');
                    $nombre = !is_null($nombre) ? 
                        Number::occident($nombre, 0) : '???';
                    $taille = Disk::size($dir, true, '.kernel');
                    $taille = !is_null($taille) ? 
                        Memory::convert($taille) : '???';

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
            Output::print($count, Output::COLOR_FORE_CYAN);
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
        $project = basename(System::root());
        $version = Program::CODY_VERSION;
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $user = getenv('username');

        Item::replace('PROJECT_NAME', $project, $p_file);
        Item::replace('PROJECT_VERSION', $version, $p_file);
        Output::print('Fichier : "');
        Output::print($p_file, Output::COLOR_FORE_CYAN);
        Output::printLn('" modifié.');

        Item::replace('PROJECT_CREATED', $date, $p_file);
        Item::replace('PROJECT_AUTHOR', $user, $p_file);
        Item::replace('PROJECT_NAME', $project, $c_file);
        Item::replace('PROJECT_AUTHOR', $user, $c_file);
        Output::print('Fichier : "');
        Output::print($c_file, Output::COLOR_FORE_CYAN);
        Output::printLn('" modifié.');

        Output::successLn("Projet initialisé avec succès.");
    }

}

?>