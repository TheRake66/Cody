<?php

namespace Kernel\Debug;

use Kernel\Communication\Network;
use Kernel\Security\Configuration;
use Kernel\Io\Convert\Encoded;
use Kernel\Io\Convert\Number;
use Kernel\Io\Path;



/**
 * Librairie gérant le journal de logs.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.1.1.0
 * @package Kernel\Debug
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Log {

	/**
     * @var string Les niveaux de criticité.
	 */
    const LEVEL_INFO     = 'INFO';
    const LEVEL_GOOD     = 'GOOD';
    const LEVEL_WARNING  = 'WARNING';
    const LEVEL_ERROR    = 'ERROR';
    const LEVEL_PROGRESS = 'PROGRESS';
    

    /**
     * @var int Les types de log.
     */
    const TYPE_NONE             = 0;
    const TYPE_QUERY            = 1;
    const TYPE_QUERY_PARAMETERS = 2;
    const TYPE_QUERY_RESULTS    = 3;
    const TYPE_API              = 1;
    const TYPE_API_PARAMETERS   = 2;
    const TYPE_API_RESPONSE     = 3;
    const TYPE_MAIL             = 4;
    const TYPE_MAIL_HEADER      = 5;
    const TYPE_MAIL_CONTENT     = 6;


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
     * Ajoute un log de type erreur dans la console et dans un fichier.
     * 
     * @param string $message Le message à logger.
     * @param int $type Le type de log.
     * @return void
     */
    static function error($message, $type = self::TYPE_NONE) {
        self::add($message, self::LEVEL_ERROR, $type);
    }


    /**
     * Ajoute un log de type avertissement dans la console et dans un fichier.
     * 
     * @param string $message Le message à logger.
     * @param int $type Le type de log.
     * @return void
     */
    static function warning($message, $type = self::TYPE_NONE) {
        self::add($message, self::LEVEL_WARNING, $type);
    }


    /**
     * Ajoute un log de type succès dans la console et dans un fichier.
     * 
     * @param string $message Le message à logger.
     * @param int $type Le type de log.
     * @return void
     */
    static function good($message, $type = self::TYPE_NONE) {
        self::add($message, self::LEVEL_GOOD, $type);
    }


    /**
     * Ajoute un log de type information dans la console et dans un fichier.
     * 
     * @param string $message Le message à logger.
     * @param int $type Le type de log.
     * @return void
     */
    static function info($message, $type = self::TYPE_NONE) {
        self::add($message, self::LEVEL_INFO, $type);
    }


    /**
     * Ajoute un log de type progression dans la console et dans un fichier.
     * 
     * @param string $message Le message à logger.
     * @param int $type Le type de log.
     * @return void
     */
    static function progress($message, $type = self::TYPE_NONE) {
        self::add($message, self::LEVEL_PROGRESS, $type);
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
        $error = false;

        if ($conf->use_file &&
            ($type !== self::TYPE_QUERY 			|| $conf->query->enabled) &&
            ($type !== self::TYPE_QUERY_PARAMETERS 	|| $conf->query->parameters) &&
            ($type !== self::TYPE_QUERY_RESULTS 	|| $conf->query->results) &&
            ($type !== self::TYPE_API 			    || $conf->api->enabled) &&
            ($type !== self::TYPE_API_PARAMETERS 	|| $conf->api->parameters) &&
            ($type !== self::TYPE_API_RESPONSE 	    || $conf->api->response) &&
            ($type !== self::TYPE_MAIL 				|| $conf->mail->enabled) &&
            ($type !== self::TYPE_MAIL_HEADER 		|| $conf->mail->header) &&
            ($type !== self::TYPE_MAIL_CONTENT 		|| $conf->mail->content)
            ) {

            $folder = Path::absolute('logs');
            if ($conf->ip_identify) {
                $ip = Network::client();
                $folder .= '/' . str_replace(':', '-', $ip);
            }

            if (is_dir($folder) || mkdir($folder, 0777, true)) {

                $now = \DateTime::createFromFormat('U.u', microtime(true));
                if ($now) {
                    $nowFull = $now->format('Y-m-d H:i:s,v');
                    $nowLite = $now->format('D M d');
                } else {
                    $nowFull = '????-??-?? ??:??:??,???';
                    $nowLite ='### ### ##';
                }

                $uuid = uniqid();
                if (is_null(self::$uuid)) {
                    self::$uuid = $uuid;
                }

                $level = Encoded::fill(Encoded::cut($level, 8), 8);
                
                if (is_object($message) || is_array($message)) {
                    $message = print_r($message, true);
                }

                $max = $conf->max_lenght;
                if ($max > 0 && !empty($message)) {
                    $len = strlen($message);
                    if ($len > $max) {
                        $diff = $len - $max;
                        $sub = substr($message, 0, $max);
                        $occ = Number::occident($diff);
                        $plur1 = Encoded::plural($diff, 'caractère');
                        $plur2 = Encoded::plural($diff, 'restant');
                        $message = "$sub ...[plus de $occ $plur1 $plur2.]";
                    }
                }

                $message = "[$nowFull] [$uuid] [$level] $message" . PHP_EOL;

                $file = $folder. '/' . $nowLite . '.log';
                Error::remove();
                $error = !file_put_contents($file, $message, FILE_APPEND | LOCK_EX);
                Error::handler();
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