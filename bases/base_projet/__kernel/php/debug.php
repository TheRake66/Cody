<?php
// Librairie Debug
namespace Kernel;



class Debug {


    /**
     * Ajoute une log dans la console
     * 
     * @param string le message a afficher
     * @param int le niveau de criticite
     */
    static function log($message, $level = 0) {
        \Kernel\Suppervisor::log($message, $level);
    }
	

    /**
     * Ajoute un message dans un fichier log
     * 
     * @param string Message a ajouter
     */
	static function file($leMessage) {
        $continu = true;
        if (!is_dir('logs')) {
            $continu = mkdir('logs');
        }
        if ($continu) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            file_put_contents(
                'logs/' . $now->format('D M d') . '.log',
                '[' . $now->format('D M d, Y H:i:s.v') . '] ' . $leMessage . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }
	}

}

?>