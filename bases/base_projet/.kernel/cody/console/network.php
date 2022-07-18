<?php
namespace Cody\Console;

use Cody\Io\Environnement;
use Kernel\Io\Convert\Memory;
use Kernel\Io\Disk;

/**
 * Librairie gérant les commandes du programme.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Network {

    /**
     * Télécharge un fichier depuis l'URL spécifiée.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function download($args) {
        if (isset($args[0]) && isset($args[1])) {
            Output::printLn("Téléchargement du fichier...");
            $url = $args[0];
            $path = $args[1];
            $file = file_get_contents($url);
            if ($file !== false) {
                if (file_put_contents($path, $file) !== false) {
                    Output::printLn("Le fichier a été téléchargé.");
                } else {
                    Output::printLn("Impossible de créer le fichie sur le disque.");
                }
            } else {
                Output::printLn("Erreur lors du téléchargement du fichier.");
            }
        } else {
            Output::printLn("Erreur : il faut deux arguments : l'URL et le chemin de destination.");
        }
    }

}


?>