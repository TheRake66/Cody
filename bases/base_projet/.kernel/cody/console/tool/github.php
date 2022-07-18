<?php
namespace Cody\Console\Tool;

use Cody\Console\Output;
use Kernel\Io\Thread;



/**
 * Librairie gérant GitHub.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console\Tool
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Github {

    /**
     * Ouvre le dépôt de Cody dans GitHub.
     * 
     * @return void
     */
    static function cody() {
        Output::printLn('Ouverture du dépôt de Cody...');
        Thread::open('start https://github.com/TheRake66/Cody');
    }

}

?>