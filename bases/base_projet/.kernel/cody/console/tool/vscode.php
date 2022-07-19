<?php
namespace Cody\Console\Tool;

use Cody\Console\Output;
use Kernel\Io\Thread;



/**
 * Librairie gérant VSCode.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console\Tool
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Vscode {

    /**
     * Ouvre le projet dans Visual Studio Code.
     * 
     * @return void
     */
    static function open() {
        Output::printLn('Ouverture de Visual Studio Code...'); 
        Thread::open('code .');
        Output::successLn('Ouverture réussie.');
    }
    
}


?>