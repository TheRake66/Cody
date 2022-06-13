<?php
namespace Kernel\DataBase;

use Kernel\Database\Statement;
use Kernel\Database\Translate;
use Kernel\Debug\Log;



/**
 * Librairie de sortie de données.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Output {

    /**
     * Prépare, exécute et retourne une requête.
     * 
     * @param string $sql La requête SQL.
     * @param mixed $params Les paramètres de la requête.
     * @return PDOStatement La requête préparée.
     */
    static function send($sql, $params) {
        Log::add('Exécution de la requête SQL : "' . $sql . '"...', Log::LEVEL_PROGRESS, Log::TYPE_QUERY);
        $rqt = Statement::instance()->prepare($sql);
        if (is_null($params)) {
            $rqt->execute();
        } else {
            $parsed = Translate::format($params);
            Log::add('Paramètres de la requête SQL : "' . print_r($parsed, true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_PARAMETERS);
            $rqt->execute(is_array($parsed) ? $parsed : [ $parsed ]);
        }
        Log::add('Requête SQL exécutée.', Log::LEVEL_GOOD, Log::TYPE_QUERY);
        return $rqt;
    }


    /**
     * Retourne le résultat puis l'enregistre dans journal de logs.
     * 
     * @param mixed $data Le résultat de la requête.
     * @return mixed Le résultat de la requête.
     */
    static function log($data) {
        Log::add('Résultat de la requête SQL : "' . print_r($data, true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_RESULTS);
        return $data;
    }

}

?>