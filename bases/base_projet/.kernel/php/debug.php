<?php
namespace Kernel;



// Librairie Debug
class Debug {

	/**
	 * Les niveaux de criticite
	 */
    const LEVEL_INFO = 0;
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
    static function log($message, $level = self::LEVEL_INFO) {
        Suppervisor::log($message, $level);
        self::file($message, $level);
    }


    /**
     * Ajoute un separateur dans le fichier log
     */
    static function separator() {
        self::file('--------------------------------------------------------');
    }


    /**
     * Ajoute une log un fichier
     * 
     * @param string le message a afficher
     * @param int le niveau de criticite
     */
    static function file($message, $level = self::LEVEL_INFO) {
        $conf = Configuration::get()->log;
        if ($conf->use_log_file) {

            $error = false;
            $folder = 'logs';
            if ($conf->ip_identify) {
                $folder .= '/' . str_replace(':', '-', Server::getClientIP());
            }
            $levelstr = '';
            switch ($level) {
                case self::LEVEL_INFO:
                    $levelstr = 'INFO    ';
                    break;
                case self::LEVEL_GOOD:
                    $levelstr = 'GOOD    ';
                    break;
                case self::LEVEL_WARN:
                    $levelstr = 'WARN    ';
                    break;
                case self::LEVEL_ERROR:
                    $levelstr = 'ERROR   ';
                    break;
                case self::LEVEL_PROGRESS:
                    $levelstr = 'PROGRESS';
                    break;
            }

            if (is_dir($folder) || mkdir($folder, 0777, true)) {

                $now = \DateTime::createFromFormat('U.u', microtime(true));
                $file = $folder. '/' . ($now ? $now->format('D M d') : '### ### ##') . '.log';
                $message = '[' . ($now ? $now->format('Y-m-d H:i:s,v') : '????-??-?? ??:??:??,???') . '] [' . $levelstr . '] ' . $message . PHP_EOL;
                
                Error::remove();
                $_ = file_put_contents($file, $message, FILE_APPEND | LOCK_EX);
                Error::handler();
                
                if ($_ === false) {
                    $error = true;
                }
            } else {
                $error = true;
            }

            if ($error) {
                trigger_error("Impossible d'accéder au journal d'événement !");
            }
        }
    }

}

?>