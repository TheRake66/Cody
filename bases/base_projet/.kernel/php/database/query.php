<?php
namespace Kernel\Database;
use Kernel\DataBase\Output;
use PDO;



/**
 * Librairie de gerant l'execution des requetes SQL
 */
class Query {

    /**
     * Execture une requete de mise a jour
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return bool si la requete a reussite
     */
    static function execute($sql, $params = []) {
        return Output::returnLog(Output::send($sql, $params)->errorCode() === '00000');
    }

    
    /**
     * Retourne une ligne
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array la ligne retournee
     */
    static function fetchRow($sql, $params = []) {
        return Output::returnLog(Output::send($sql, $params)->fetch(PDO::FETCH_ASSOC));
    }

    
    /**
     * Retourne plusieurs lignes
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array les lignes retournees
     */
    static function fetchAll($sql, $params = []) {
        return Output::returnLog(Output::send($sql, $params)->fetchAll(PDO::FETCH_ASSOC));
    }

    
    /**
     * Retourne une valeur
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return mixed valeur de la cellule
     */
    static function fetchCell($sql, $params = []) {
        $res = Output::send($sql, $params)->fetch(PDO::FETCH_ASSOC);
        if (!is_null($res) && !empty($res)) {
            return Output::returnLog(array_values($res)[0]);
        }
    }

    
    /**
     * Recupere une ligne et l'hydrate dans un objet DTO
     * 
     * @param string requete sql
     * @param object la classe DTO a hydrater
     * @param array la liste des parametres
     * @return object objet DTO hydrate
     */
    static function fetchObject($sql, $class, $params = []) {
        $_ = Output::send($sql, $params);
        $_->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
        return Output::returnLog($_->fetch());
    }

    
    /**
     * Recupere plusieurs lignes et les hydrate dans une liste d'objet DTO
     * 
     * @param string requete sql
     * @param object la classe DTO a hydrater
     * @param array la liste des parametres
     * @return array liste d'objets DTO hydrates
     */
    static function fetchObjects($sql, $class, $params = []) {
        return Output::returnLog(Output::send($sql, $params)
        ->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class));
    }

}

?>