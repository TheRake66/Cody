<?php
namespace Kernel;
use Exception;



// Librairie Debug
class Debug {

	/**
	 * Les niveaux de criticite
	 */
    const LEVEL_OK = 0;
    const LEVEL_GOOD = 1;
    const LEVEL_WARN = 2;
    const LEVEL_ERROR = 3;
    const LEVEL_PROGRESS = 4;


    /**
     * Ajoute une log dans la console et dans un fichier
     * 
     * @param string le message a afficher
     * @param int le niveau de criticite
     */
    static function log($message, $level = self::LEVEL_OK) {
        Suppervisor::log($message, $level);
        if (Configuration::get()->use_log_file) {
            if (is_dir('logs') || mkdir('logs')) {
                $now = \DateTime::createFromFormat('U.u', microtime(true));
                Error::remove();
                $_ = file_put_contents(
                    'logs/' . Server::getClientIP() . '/' . $now->format('D M d') . '.txt',
                    '[' . $now->format('D M d, Y H:i:s.v') . '] [LEVEL:' . $level . '] ' . $message . PHP_EOL,
                    FILE_APPEND | LOCK_EX
                );
                Error::handler();
                if ($_ === false) {
                    trigger_error("Impossible d'accéder au journal d'événement !");
                }
            }
        }
    }

}

?>