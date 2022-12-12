<?php
namespace Kernel\Debug;

use Kernel\Security\Configuration;
use Kernel\Io\Convert\Number;
use Kernel\Communication\Network;
use Kernel\Io\Path;



/**
 * Librairie gérant le journal de logs.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Debug
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Log {

	/**
     * @var int Les niveaux de criticité.
	 */
    const LEVEL_INFO = 0;
    const LEVEL_GOOD = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR = 3;
    const LEVEL_PROGRESS = 4;
    

    /**
     * @var int Les types de log.
     */
    const TYPE_NONE = 0;
    const TYPE_QUERY = 1;
    const TYPE_QUERY_PARAMETERS = 2;
    const TYPE_QUERY_RESULTS = 3;
    const TYPE_MAIL = 4;
    const TYPE_MAIL_HEADER = 5;
    const TYPE_MAIL_CONTENT = 6;


    /**
     * @var int L'identifiant unique de la session de log.
     */
    private static $uuid;


    /**
     * Ajoute un log dans la console et dans un fichier.
     * 
     * @param string $message Le message à logger.
     * @param int $level Le niveau de criticité du message.
     * @param int $type Le type de log.
     * @return void
     */
    static function add($message, $level = self::LEVEL_INFO, $type = self::TYPE_NONE) {
        Supervisor::log($message, $level);
        self::file($message, $level, $type);
    }


    /**
     * Ajoute une log un fichier.
     * 
     * @param string $message Le message à logger.
     * @param int $level Le niveau de criticité du message.
     * @param int $type Le type de log.
     * @return void
     * @throws Error Si le fichier de log n'est pas accessible.
     */
    static function file($message, $level = self::LEVEL_INFO, $type = self::TYPE_NONE) {
        $conf = Configuration::get()->log;

        if ($conf->use_file &&
            ($type !== self::TYPE_QUERY 			|| $conf->query->enabled) &&
            ($type !== self::TYPE_QUERY_PARAMETERS 	|| $conf->query->parameters) &&
            ($type !== self::TYPE_QUERY_RESULTS 	|| $conf->query->results) &&
            ($type !== self::TYPE_MAIL 				|| $conf->mail->enabled) &&
            ($type !== self::TYPE_MAIL_HEADER 		|| $conf->mail->header) &&
            ($type !== self::TYPE_MAIL_CONTENT 		|| $conf->mail->content)
            ) {

            $folder = Path::absolute('logs');
            if ($conf->ip_identify) {
                $folder .= '/' . str_replace(':', '-', Network::ip());
            }

            $levelstr = '';
            switch ($level) {
                case self::LEVEL_INFO:
                    $levelstr = 'INFO    ';
                    break;
                case self::LEVEL_GOOD:
                    $levelstr = 'GOOD    ';
                    break;
                case self::LEVEL_WARNING:
                    $levelstr = 'WARNING ';
                    break;
                case self::LEVEL_ERROR:
                    $levelstr = 'ERROR   ';
                    break;
                case self::LEVEL_PROGRESS:
                    $levelstr = 'PROGRESS';
                    break;
            }

            $error = false;
            if (is_dir($folder) || mkdir($folder, 0777, true)) {

                $now = \DateTime::createFromFormat('U.u', microtime(true));
                if ($now) {
                    $now_full = $now->format('Y-m-d H:i:s,v');
                    $now_lite = $now->format('D M d');
                } else {
                    $now_full = '????-??-?? ??:??:??,???';
                    $now_lite ='### ### ##';
                }
                $file = $folder. '/' . $now_lite . '.log';
                if (is_object($message) || is_array($message)) {
                    $message = print_r($message, true);
                }
                $max = $conf->max_lenght;
                if ($max > 0 && !empty($message)) {
                    $len = strlen($message);
                    if ($len > $max) {
                        $message = substr($message, 0, $max) . ' ...[plus de ' . Number::occident($len - $max, 0) . ' caractère(s) restant(s)]';
                    }
                }            
                if (is_null(self::$uuid)) {
                    self::$uuid = uniqid();
                }
                $id = self::$uuid;
                $message = '[' . $now_full . '] [' . $id . '] [' . $levelstr . '] ' . $message . PHP_EOL;
                
                Error::remove();
                $_ = file_put_contents($file, $message, FILE_APPEND | LOCK_EX);
                Error::handler();
                
                if ($_ === false) {
                    $error = true;
                }
            } else {
                $error = true;
            }

            if ($error && $conf->throw_if_unwritable) {
                Error::trigger("Impossible d'accéder au journal d'événement !");
            }
        }
    }

}

?>