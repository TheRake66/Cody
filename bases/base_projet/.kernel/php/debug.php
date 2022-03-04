<?php
namespace Kernel;



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

        $conf = Configuration::get()->log;
        if ($conf->use_log_file) {

            $error = false;
            $folder = 'logs';
            if ($conf->ip_identify) {
                $folder .= '/' . str_replace(':', '-', Server::getClientIP());
            }
            $levelstr = '';
            switch ($level) {
                case self::LEVEL_OK:
                    $levelstr = 'OK      ';
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
                $file = $folder. '/' . $now->format('D M d') . '.txt';
                $message = '[' . $now->format('D M d, Y H:i:s.v') . '] [LEVEL : ' . $levelstr . '] ' . $message . PHP_EOL;
                
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