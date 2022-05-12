<?php
namespace Kernel\DataBase;
use Kernel\Debug;
use Kernel\Database\Statement;
use Kernel\Database\Translate;



/**
 * Librairie de sortie de donnees
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Librarie
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
        Debug::log('Exécution de la requête SQL : "' . $sql . '"...', Debug::LEVEL_PROGRESS, Debug::TYPE_QUERY);
        $parsed = Translate::formatMany($params);
        Debug::log('Paramètres de la requête SQL : "' . print_r($parsed, true) . '".', Debug::LEVEL_INFO, Debug::TYPE_QUERY_PARAMETERS);
        $rqt = Statement::getInstance()->prepare($sql);
        $rqt->execute($parsed);
        Debug::log('Requête SQL exécutée.', Debug::LEVEL_GOOD, Debug::TYPE_QUERY);
        return $rqt;
    }


    /**
     * Retourne le resultat puis l'enregistre dans la log
     * 
     * @param mixed le resultat de la requete
     * @return mixed le resultat de la requete
     */
    static function returnLog($data) {
        Debug::log('Résultat de la requête SQL : "' . print_r($data, true) . '".', Debug::LEVEL_INFO, Debug::TYPE_QUERY_RESULTS);
        return $data;
    }

}

?>