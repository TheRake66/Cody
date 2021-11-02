<?php

namespace Librairie;



class DataBase extends \PDO {
    
    /**
     * Instance PDO
     */
    static $instance;


    /**
     * Retourne l'inctance PDO en cours, si aucune est
     * en cours on en creer une
     */
    static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DataBase();
        }
        return self::$instance;
    }
    
    
    /**
     * Creer une instance PDO
     */
    function __construct() {
        try {
            $param = json_decode(file_get_contents('modele/database.json'));
            $dsn = $param->type . ':host=' . $param->host . ';dbname=' . $param->dbname . ';charset=' . $param->charset;
            parent::__construct($dsn, $param->login, $param->password);
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    
    /**
     * Prepare et retourne une requete
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return object requete preparee
     */
    static function send($sql, $params = []) {
        $rqt = self::getInstance()->prepare($sql, $params);
        return $rqt;
    }

    
    /**
     * Retourne une ligne
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array ligne de la base
     */
    static function fetchRow($sql, $params = []) {
        $rqt = self::send($sql, $params);
        $rqt->execute();
        return $rqt->fetch();
    }

    
    /**
     * Execture une requete de mise a jour
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return bool si la requete a reussite
     */
    static function execute($sql, $params = []) {
        $rqt = self::send($sql, $params);
        return $rqt->execute();
    }

    
    /**
     * Retourne une valeur
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return object valeur de la base
     */
    static function fetchCell($sql, $params = []) {
        $rqt = self::send($sql, $params);
        $rqt->execute();
        return $rqt->fetch()[0];
    }

    
    /**
     * Retourne plusieurs lignes
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array les lignes de la base
     */
    static function fetchAll($sql, $params = []) {
        $rqt = self::send($sql, $params);
        $rqt->execute();
        return $rqt->fetchAll();
    }

    
    /**
     * Recupere une ligne et l'hydrate dans un objet
     * 
     * @param string requete sql
     * @param object type d'objet a retourne
     * @param array liste des parametres
     * @return object objet hydrate
     */
    static function fetchObjet($sql, $type, $params = []) {
        $rep = self::fetchRow($sql, $params);
        $obj = new $type();
        $obj->hydrate($rep);
        return $obj;
    }

    
    /**
     * Recupere plusieurs lignes et les hydrate dans une liste d'objet
     * 
     * @param string requete sql
     * @param object type d'objet a retourne
     * @param array liste des parametres
     * @return array liste d'objets hydrate
     */
    static function fetchObjets($sql, $type, $params = []) {
        $rep = self::fetchAll($sql, $params);
        $arr = [];
        if (!is_null($rep) && !empty($rep)) {
			foreach ($rep as $r) {
				$obj = new $type();
				$obj->hydrate($r);
				$arr[] = $obj;
			}
        }
        return $arr;
    }

}

?>