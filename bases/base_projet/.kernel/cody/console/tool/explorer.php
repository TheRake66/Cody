<?php
namespace Cody\Console\Tool;

use Cody\Console\Output;
use Cody\Console\Project;
use Kernel\Io\Thread;
use Kernel\Communication\Network\Download;
use Kernel\Environnement\Configuration;
use Kernel\Environnement\System;

/**
 * Librairie gérant l'explorateur de fichiers.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console\Tool
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Explorer {

    /**
     * Recharge la configuration du framework.
     * 
     * @return void
     */
    static function reload() {
        Output::printLn('Rechargement de la configuration...');
        if (Configuration::load()) {
            Output::successLn('Configuration rechargée.');
        } else {
            Output::errorLn('Impossible de recharger la configuration !');
        }
    }


    /**
     * Ouvre le dossier courant dans l'explorateur de fichiers.
     * 
     * @return void
     */
    static function open() {
        Output::printLn('Ouverture de l\'explorateur de fichiers...');
        Thread::open('start .');
        Output::successLn('Ouverture réussie.');
    }


    /**
     * Change le dossier courant par celui du projet.
     * 
     * @return void
     */
    static function root() {
        Output::printLn('Retour au dossier du projet...');
        chdir(System::root());
        Output::successLn('Retour réussi.');
    }


    /**
     * Change le dossier courant par celui spécifié. 
     * 
     * @param string $dir Le dossier à utiliser.
     * @return void
     */
    static function change($dir) {
        if (is_dir($dir)) {
            chdir($dir);
            Output::successLn('Dossier changé.');
        } else {
            Output::errorLn('Ce dossier n\'existe pas !');
        }
    }


    /**
     * Affiche la liste des fichiers et des dossiers du dossier courant.
     * 
     * @return void
     */
    static function list() {
        $dir = rtrim(getcwd(), '/') . '/*';
        $dirs = glob($dir, GLOB_ONLYDIR);
        $files = glob($dir);
        $alls = array_unique(array_merge($dirs, $files));
        
        $longest = 0;
        foreach ($alls as $element) {
            $base = basename($element);
            $len = strlen($base);
            if ($len > $longest) {
                $longest = $len;
            }
        }
        $longest += 3;

        $count = 0;
        foreach ($alls as $element) {
            $base = basename($element);
            $len = strlen($base);
            $margin = $longest - $len;
            $space = str_repeat(' ', $margin);
            $count += $len + $margin;
            $output = $base . $space;
            $color = is_dir($element) ? 
                (Project::is($element) ?
                    Output::COLOR_FORE_MAGENTA : 
                    Output::COLOR_FORE_BLUE) :
                Output::COLOR_FORE_CYAN;

            Output::print($output, $color);

            if ($count >= Configuration::get()->console->max_width) {
                Output::break();
                $count = 0;
            }
        }
        
        Output::break();
    }
    

    /**
     * Télécharge un fichier depuis l'URL spécifiée.
     * 
     * @param string $url L'URL du fichier à télécharger.
     * @param string $file Le chemin du fichier à télécharger.
     * @return void
     */
    static function download($url, $file) {
        Output::printLn('Téléchargement du fichier...');
        if (Download::get($url, $file)) {
            Output::successLn('Téléchargement réussi !');
        } else {
            Output::errorLn('Téléchargement échoué ! Veuillez vérifier l\'URL.');
        }
    }
    
}


?>