<?php
namespace Kernel\DataBase;

use Kernel\Database\Statement;
use Kernel\Database\Translate;
use Kernel\Debug\Log;



/**
 * Librairie de sortie de donnees
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Output {

    /**
     * Prepare, execute et retourne une requete
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return PDOStatement instance PDO
     */
    static function send($sql, $params) {
        Log::add('Exécution de la requête SQL : "' . $sql . '"...', Log::LEVEL_PROGRESS, Log::TYPE_QUERY);
        $parsed = Translate::formatMany($params);
        Log::add('Paramètres de la requête SQL : "' . print_r($parsed, true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_PARAMETERS);
        $rqt = Statement::getInstance()->prepare($sql);
        $rqt->execute($parsed);
        Log::add('Requête SQL exécutée.', Log::LEVEL_GOOD, Log::TYPE_QUERY);
        return $rqt;
    }


    /**
     * Retourne le resultat puis l'enregistre dans la log
     * 
     * @param mixed le resultat de la requete
     * @return mixed le resultat de la requete
     */
    static function return($data) {
        Log::add('Résultat de la requête SQL : "' . print_r($data, true) . '".', Log::LEVEL_INFO, Log::TYPE_QUERY_RESULTS);
        return $data;
    }

}

?>