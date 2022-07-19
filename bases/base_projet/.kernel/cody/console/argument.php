<?php
namespace Cody\Console;

use Cody\Console\Tool\Explorer;
use Cody\Console\Tool\Github;
use Cody\Console\Tool\Php;
use Cody\Console\Tool\Vscode;

/**
 * Librairie gérant les arguments de commandes.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Argument {


    /**
     * Découpe les arguments d'une ligne de commande.
     * 
     * @param string $line Ligne de commande.
     * @return array Tableau des arguments.
     */
    static function parse($line) {
        $char = str_split($line);
        $quote = false;
        for ($i = 0; $i < count($char); $i++) {
            if ($char[$i] == '"' || $char[$i] == "'") {
                $quote = !$quote;
            }
            if (!$quote && $char[$i] === ' ') {
                $char[$i] = "\n";
            }
        }
        $args = explode("\n", implode($char));
        return $args;
    }


    /**
     * Vérifie si aucun argument est spécifié.
     * 
     * @param function $callback Fonction à appeler si aucun argument n'est spécifié.
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function empty($callback, $args) {
        if (empty($args)) {
            $callback();
        } else {
            Output::errorLn("Erreur, aucun argument n'est attendu !");
        }
    }


    /**
     * Vérifie si un nombre d'argument précis est spécifié.
     * 
     * @param int $count Nombre d'arguments attendus.
     * @param function $callback Fonction à appeler si aucun argument n'est spécifié.
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function count($count, $callback, $args) {
        if (count($args) === $count) {
            $callback();
        } else {
            Output::errorLn("Erreur, " . $count . " argument(s) sont attendus !");
        }
    }


    /**
     * Appelle le bon callback en fonction du nombre d'argument.
     * 
     * @param array $callback Dictionnaire avec les nombre d'arguments et leur callback.
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function match($callbacks, $args) {
        $count = count($args);
        if (isset($callbacks[$count])) {
            $callbacks[$count]();
        } else {
            Output::errorLn("Erreur, nombre d'arguments incorrect !");
        }
    }

}


?>