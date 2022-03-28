<?php
namespace Kernel;



/**
 * Librairie gerant les logs
 */
class Debug {

	/**
	 * Les niveaux de criticite
     * 
     * @var int
	 */
    const LEVEL_INFO = 0;
    const LEVEL_GOOD = 1;
    const LEVEL_WARN = 2;
    const LEVEL_ERROR = 3;
    const LEVEL_PROGRESS = 4;


    /**
     * Les types de log
     * 
     * @var int
     */
    const TYPE_NONE = 0;
    const TYPE_QUERY = 1;
    const TYPE_QUERY_PARAMETERS = 2;
    const TYPE_QUERY_RESULTS = 3;


    /**
     * Ajoute une log dans la console et dans un fichier
     * 
     * @param string le message a afficher
     * @param int le niveau de criticite
     * @param int le type de log
     * @return void
     */
    static function log($message, $level = self::LEVEL_INFO, $type = self::TYPE_NONE) {
        Supervisor::log($message, $level);
        self::file($message, $level, $type);
    }


    /**
     * Ajoute une log un fichier
     * 
     * @param string le message a afficher
     * @param int le niveau de criticite
     * @param int le type de log
     * @return void
     */
    static function file($message, $level = self::LEVEL_INFO, $type = self::TYPE_NONE) {
        $conf = Configuration::get()->log;
        $confq = $conf->query;

        if ($conf->use_log_file &&
            ($type !== self::TYPE_QUERY || $confq->print_query) &&
            ($type !== self::TYPE_QUERY_PARAMETERS || $confq->print_parameters) &&
            ($type !== self::TYPE_QUERY_RESULTS || $confq->print_results)) {

            $error = false;
            $folder = 'logs';
            if ($conf->ip_identify) {
                $folder .= '/' . str_replace(':', '-', Server::getClientIp());
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
                if (is_object($message) || is_array($message)) {
                    $message = print_r($message, true);
                }
                $max = $conf->max_lenght;
                if ($max > 0) {
                    $message = mb_strimwidth($message, 0, $max, ' ... [plus de ' . (strlen($message) - $max) . ' caractère(s) restant(s)]');
                }
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


    /**
     * Ajoute un separateur dans le fichier log
     * 
     * @return void
     */
    static function separator() {
        self::file('--------------------------------------------------------');
    }

}

?>