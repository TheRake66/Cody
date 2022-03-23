<?php
namespace Kernel;
use DateTime;



/**
 * Librairie de connexion et de traitement (CRUD) a la base de donnees
 */
class DataBase extends \PDO {
    
    /**
     * Instance PDO
     */
    private static $instance;
    
    
    /**
     * Creer une instance PDO
     *
     * @param array liste des attribut PDO
     */
    private function __construct($options = []) {
        Debug::log('Connexion à la base de données...', Debug::LEVEL_PROGRESS);
        try {
            $conf = Configuration::get()->database;
            $dsn = $conf->type . 
                ':host=' . $conf->host . 
                ';port=' . $conf->port . 
                ';dbname=' . $conf->name . 
                ';charset=' . $conf->encoding;
            parent::__construct(
                $dsn, 
                $conf->login,  
                $conf->password, 
                $options);
        } catch (\Exception $e) {
            trigger_error('Impossible de se connecter à la base de données, message : "' . $e->getMessage() . '".');
        }
        Debug::log('Connexion réussite.', Debug::LEVEL_GOOD);
    }


    /**
     * Retourne l'inctance PDO en cours, si aucune est
     * en cours on en creer une
     * 
     * @return object instance PDO
     */
    private static function getInstance() {
        if (!self::$instance) {
            $conf = Configuration::get()->database;
            $options = [
                parent::ATTR_PERSISTENT => $conf->persistent_mode,
                parent::ATTR_EMULATE_PREPARES => $conf->emulate_prepare,
                parent::ATTR_ERRMODE => $conf->show_sql_error ?
                        parent::ERRMODE_EXCEPTION :
                        parent::ERRMODE_SILENT
            ];
            self::$instance = new DataBase($options);
        }
        return self::$instance;
    }
    

    /**
     * Prepare, execute et retourne une requete
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @param object classe si on veut un objet
     * @return object requete executee
     */
    private static function send($sql, $params, $class = null) {
        $parsed = self::paramsToSQL($params);
        Debug::log('Exécution de la requête SQL : "' . $sql . '"...', Debug::LEVEL_PROGRESS, Debug::TYPE_QUERY);
        Debug::log('Paramètres de la requête SQL : "' . print_r($parsed, true) . '".', Debug::LEVEL_INFO, Debug::TYPE_QUERY_PARAMETERS);
        $rqt = self::getInstance()->prepare($sql);
        if (!is_null($class)) {
            $rqt->setFetchMode(parent::FETCH_INTO, new $class());
        }
        $rqt->execute($parsed);
        Debug::log('Requête SQL exécutée.', Debug::LEVEL_GOOD, Debug::TYPE_QUERY);
        return $rqt;
    }


    private static function returnLog($data) {
        Debug::log('Résultat de la requête SQL : "' . print_r($data, true) . '".', Debug::LEVEL_INFO, Debug::TYPE_QUERY_RESULTS);
        return $data;
    }


    /**
     * Convertit un parametre en parametre SQL
     * 
     * @param object le parametre
     * @return object le parametre en SQL
     */
    private static function paramToSQL($param) {
        if ($param instanceof DateTime) {
            return $param->format('Y-m-d H:i:s');
        } elseif (is_bool($param)) {
            return $param ? 1 : 0;
        } else {                
            return $param;
        }
    }


    /**
     * Convertit un array de parametres en parametres SQL
     * 
     * @param array les parametres
     * @return array les parametres en SQL
     */
    private static function paramsToSQL($params) {
        $parsed = [];
        foreach ($params as $param) {
            $parsed[] = self::paramToSQL($param);
        }
        return $parsed;
    }

    
    /**
     * Construit la condition WHERE pour les cle primaire
     * 
     * @param object l'objet DTO a lier
     * @param array les nom des cles primaire
     */
    private static function buildClause($obj, $clause = null) {
        $sql = '';
        $arr = [];
        if (is_null($clause)) {
            $clause = $obj::PRIMARY;
        }
        foreach ((array)$obj as $prop => $val) {
            if (!is_array($clause) || count($clause) == 0) {
                $sql .= 'WHERE ' . str_replace('*', '', $prop) . ' = ? ';
                $arr[] = $val;
                break;
            } elseif (in_array($prop, $clause)) {
                $sql .= (empty($sql) ? 'WHERE' : 'AND') . ' ' . $prop . ' = ? ';
                $arr[] = $val;
            }
        }
        $len = strlen($sql);
        if ($len > 0) {
            $sql = substr($sql, 0, $len - 1);
        }
        return [ $sql, $arr ];
    }

    
    /**
     * Execture une requete de mise a jour
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return bool si la requete a reussite
     */
    static function execute($sql, $params = []) {
        return self::returnLog(self::send($sql, $params)->errorCode() === '00000');
    }

    
    /**
     * Retourne une ligne
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array ligne de la base
     */
    static function fetchRow($sql, $params = []) {
        return self::returnLog(self::send($sql, $params)->fetch(parent::FETCH_ASSOC));
    }

    
    /**
     * Retourne plusieurs lignes
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array les lignes de la base
     */
    static function fetchAll($sql, $params = []) {
        return self::returnLog(self::send($sql, $params)->fetchAll(parent::FETCH_ASSOC));
    }

    
    /**
     * Retourne une valeur
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return object valeur de la base
     */
    static function fetchCell($sql, $params = []) {
        $res = self::send($sql, $params)->fetch(parent::FETCH_ASSOC);
        if (!is_null($res) && !empty($res)) {
            return self::returnLog(array_values($res)[0]);
        }
    }

    
    /**
     * Recupere une ligne et l'hydrate dans un objet
     * 
     * @param string requete sql
     * @param object type d'objet a retourne
     * @param array liste des parametres
     * @return object objet hydrate
     */
    static function fetchObject($sql, $type, $params = []) {
        return self::returnLog(self::send($sql, $params, $type)->fetch());
    }

    
    /**
     * Recupere plusieurs lignes et les hydrate dans une liste d'objet
     * 
     * @param string requete sql
     * @param object type d'objet a retourne
     * @param array liste des parametres
     * @return array liste d'objets hydrate
     */
    static function fetchObjects($sql, $type, $params = []) {
        return self::returnLog(self::send($sql, $params)->fetchAll(parent::FETCH_CLASS | parent::FETCH_PROPS_LATE, $type));
    }


    /**
     * Retourne tous les objets d'une table
     * 
     * @param class classe DTO faisant reference a la table
     * @return array les objets DTO
     */
    static function alls($class) {
        return DataBase::fetchObjects(
			"SELECT * FROM " . self::getTableName($class),
            $class);
    }


    /**
     * Compte les lignes d'une table
     * 
     * @param class classe DTO faisant reference a la table
     * @return int le nombre de ligne
     */
    static function size($class) { 
        return DataBase::fetchCell(
            'SELECT COUNT(1) FROM ' . self::getTableName($class));
    }


    /**
     * Vide une table
     * 
     * @param class classe DTO faisant reference a la table
     * @return bool si ca reussit
     */
    static function truncat($class) { 
        return DataBase::execute('TRUNCATE TABLE ' . self::getTableName($class));
    }


    /**
     * Verifie si un resultat existe
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes dans la clause
     * @return bool si il existe
     */
    static function exists($obj, $clause = null) {
        $pr = self::buildClause($obj, $clause);
        return DataBase::fetchCell(
            'SELECT EXISTS (SELECT 1 FROM ' . self::getTableName($obj) . ' ' . $pr[0] . ')',
            $pr[1]);    
    }


    /**
     * Compte les lignes d'une table pour un objet
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes dans la clause
     * @return int le nombre de ligne
     */
    static function count($obj, $clause = null) {
        $pr = self::buildClause($obj, $clause);
        return DataBase::fetchCell(
            'SELECT COUNT(1) FROM ' . self::getTableName($obj) . ' ' . $pr[0],
            $pr[1]);    
    }


    /**
     * Creer un objet dans une table
     * 
     * @param object objet a creer
     * @return bool si ca reussit
     */
    static function create($obj) {
        $col = '';
        $pmv = '';
        $pms = [];
        foreach ((array)$obj as $prop => $val) {
            $col .= str_replace('*', '', $prop) . ', ';
            $pmv .= '?, ';
            $pms[] = $val;
        }
        $len = strlen($col);
        if ($len > 0) {
            $col = substr($col, 0, $len - 2);
        }
        $len2 = strlen($pmv);
        if ($len2 > 0) {
            $pmv = substr($pmv, 0, $len2 - 2);
        }
        return DataBase::execute(
			'INSERT INTO ' . self::getTableName($obj) . ' (' . $col . ') VALUES (' . $pmv . ')',
            $pms);
    }


    /**
     * Lis un objet dans une table
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes dans la clause
     * @return object les objets DTO
     */
    static function read($obj, $clause = null) {
        $pr = self::buildClause($obj, $clause);
        return DataBase::fetchObject(
			'SELECT * FROM ' . self::getTableName($obj) . ' ' . $pr[0],
            $obj,
            $pr[1]);
    }


    /**
     * Met a jour un objet dans une table
     * 
     * @param object objet a mettre a jour
     * @param array les proprietes dans la clause
     * @return bool si ca reussit
     */
    static function update($obj, $clause = null) {
        $set = '';
        $col = [];
        foreach ((array)$obj as $prop => $val) {
            $set .= str_replace('*', '', $prop) . ' = ?, ';
            $col[] = $val;
        }
        $pr = self::buildClause($obj, $clause);
        $len = strlen($set);
        if ($len > 0) {
            $set = substr($set, 0, $len - 2);
        }
        return DataBase::execute(
			'UPDATE ' . self::getTableName($obj) . ' SET ' . $set . ' ' . $pr[0],
            array_merge($col, $pr[1]));
    }


    /**
     * Supprime un objet dans une table
     * 
     * @param object objet a supprimer
     * @param array les proprietes dans la clause
     * @return bool si ca reussit
     */
    static function delete($obj, $clause = null) { 
        $pr = self::buildClause($obj, $clause);
        return DataBase::execute(
			'DELETE FROM ' . self::getTableName($obj) . ' ' . $pr[0],
            $pr[1]);
    }


    /**
     * Lis plusieurs objets dans une table
     * 
     * @param object objet contenant les valeurs a lire
     * @param array les proprietes dans la clause
     * @return object les objets DTO
     */
    static function readMany($obj, $clause = null) {
        $pr = self::buildClause($obj, $clause);
        return DataBase::fetchObjects(
			'SELECT * FROM ' . self::getTableName($obj) . ' ' . $pr[0],
            get_class($obj),
            $pr[1]);
    }


    /**
     * Retourne le nom d'une table via sa classe
     * 
     * @param object l'objet DTO
     * @return string le nom
     */
    static function getTableName($obj) {
        return strtolower((new \ReflectionClass($obj))->getShortName());
    }


    /**
     * Retourne null si la valeur est vide, sinon retourne la valeur
     * 
     * @param object la valeur a verifier
     * @return object null ou la valeur
     */
    static function nullIfEmpty($value) {
        return empty($value) ? null : $value;
    }

}

?>