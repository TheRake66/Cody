<?php
namespace Cody\Console;

use Cody\Console\Tool\Explorer;
use Cody\Console\Tool\Github;
use Cody\Console\Tool\Php;
use Cody\Console\Tool\Vscode;

/**
 * Librairie gérant les nombres d'arguments des commandes.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Command {

    /**
     * Dispatcher de la commande "rep".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function rep($args) {
        Argument::empty(function() {
            Github::cody();
        }, $args);
    }


    /**
     * Dispatcher de la commande "help".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function help($args) {
        Argument::match([
            0 => function() {
                Output::help();
            },
            1 => function() use ($args) {
                $command = $args[0];
                if (method_exists(Helper::class, $command)) {
                    Helper::$command();
                } else {
                    Output::help();
                }
            }
        ], $args);
    }


    /**
     * Dispatcher de la commande "init".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function init($args) {
        Argument::empty(function() {
            Project::init();
        }, $args);
    }


    /**
     * Dispatcher de la commande "ls".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function ls($args) {
        Argument::empty(function() {
            Project::list();
        }, $args);
    }


    /**
     * Dispatcher de la commande "dl".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function dl($args) {
        Argument::count(2, function() use ($args) {
            Explorer::download($args[0], $args[1]);
        }, $args);
    }


    /**
     * Dispatcher de la commande "bye".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function bye($args) {
        Argument::empty(function() {
            if (Php::running()) {
                Php::stop();
            }
            Output::clear();
            exit(0);
        }, $args);
    }


    /**
     * Dispatcher de la commande "cls".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function cls($args) {
        Argument::empty(function() {
            Output::clear();
        }, $args);
    }


    /**
     * Dispatcher de la commande "run".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function run($args) {
        Argument::empty(function() {
            Php::start();
        }, $args);
    }


    /**
     * Dispatcher de la commande "stop".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function stop($args) {
        Argument::empty(function() {
            Php::stop();
        }, $args);
    }


    /**
     * Dispatcher de la commande "vs".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function vs($args) {
        Argument::empty(function() {
            Vscode::open();
        }, $args);
    }


    /**
     * Dispatcher de la commande "exp".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function exp($args) {
        Argument::empty(function() {
            Explorer::open();
        }, $args);
    }


    /**
     * Dispatcher de la commande "php".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function root($args) {
        Argument::empty(function() {
            Explorer::root();
        }, $args);
    }


    /**
     * Dispatcher de la commande "cd".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function cd($args) {
        Argument::match([
            0 => function() {
                Explorer::list();
            },
            1 => function() use ($args) {
                Explorer::change($args[0]);
            }
        ], $args);
    }


    /**
     * Dispatcher de la commande "conf".
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function conf($args) {
        Argument::empty(function() {
            Explorer::reload();
        }, $args);
    }

}

?>