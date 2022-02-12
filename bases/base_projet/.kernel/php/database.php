<?php
namespace Kernel;
use DateTime;



// Librairie DataBase
class DataBase extends \PDO {
    
    /**
     * Instance PDO
     */
    private static $instance;


    /**
     * Retourne l'inctance PDO en cours, si aucune est
     * en cours on en creer une
     * 
     * @return object instance PDO
     */
    static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DataBase();
            self::$instance->setAttribute(parent::ATTR_ERRMODE,
                Configuration::get()->database->show_sql_error ?
                parent::ERRMODE_EXCEPTION :
                parent::ERRMODE_SILENT);
        }
        return self::$instance;
    }
    
    
    /**
     * Creer une instance PDO
     */
    function __construct() {
        Debug::log('Connexion à la base de données...', Debug::LEVEL_PROGRESS);
        try {
            $c = Configuration::get()->database;
            $dsn = $c->type . 
                ':host=' . $c->host . 
                ';port=' . $c->port . 
                ';dbname=' . $c->name . 
                ';charset=' . $c->encoding;
            parent::__construct(
                $dsn, 
                $c->login, 
                $c->password);
        } catch (\Exception $e) {
            throw new \Exception('Impossible de se connecter à la base de données, message : "' . $e->getMessage() . '".');
        }
        Debug::log('Connexion réussite.', Debug::LEVEL_GOOD);
    }


    /**
     * Convertit un parametre en parametre SQL
     * 
     * @param object le parametre
     * @return object le parametre en SQL
     */
    static function paramToSQL($param) {
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
    static function paramsToSQL($params) {
        $parsed = [];
        foreach ($params as $param) {
            $parsed[] = self::paramToSQL($param);
        }
        return $parsed;
    }
    

    /**
     * Prepare et retourne une requete
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return object requete preparee
     */
    static function send($sql) {
        Debug::log('Préparation de la requête : "' . $sql . '".');
        $rqt = self::getInstance()->prepare($sql);
        return $rqt;
    }

    
    /**
     * Execture une requete de mise a jour
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return bool si la requete a reussite
     */
    static function execute($sql, $params = []) {
        $rqt = self::send($sql);
        $parsed = self::paramsToSQL($params);
        Debug::log('Paramètres de la requête (execute) : "' . print_r($parsed, true) . '".');
        return $rqt->execute($parsed);
    }

    
    /**
     * Retourne une ligne
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array ligne de la base
     */
    static function fetchRow($sql, $params = []) {
        $rqt = self::send($sql);
        $parsed = self::paramsToSQL($params);
        Debug::log('Paramètres de la requête (row) : "' . print_r($parsed, true) . '".');
        $rqt->execute($parsed);
        return $rqt->fetch(parent::FETCH_ASSOC);
    }

    
    /**
     * Retourne plusieurs lignes
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array les lignes de la base
     */
    static function fetchAll($sql, $params = []) {
        $rqt = self::send($sql);
        $parsed = self::paramsToSQL($params);
        Debug::log('Paramètres de la requête (all) : "' . print_r($parsed, true) . '".');
        $rqt->execute($parsed);
        return $rqt->fetchAll(parent::FETCH_ASSOC);
    }

    
    /**
     * Retourne une valeur
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return object valeur de la base
     */
    static function fetchCell($sql, $params = []) {
        $rep = self::fetchRow($sql, $params);
        if (!is_null($rep) && !empty($rep)) {
            return array_values($rep)[0];
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
        $rep = self::fetchRow($sql, $params);
        if (!is_null($rep) && !empty($rep)) {
            $obj = new $type();
            $obj->hydrate($rep);
            return $obj;
        }
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
        $rep = self::fetchAll($sql, $params);
        if (!is_null($rep) && !empty($rep)) {
            $arr = [];
			foreach ($rep as $r) {
				$obj = new $type();
				$obj->hydrate($r);
				$arr[] = $obj;
			}
            return $arr;
        }
    }


    /**
     * Retourne le nom d'une table via sa classe
     * 
     * @return string le nom
     */
    static function getTableName($obj) {
        return strtolower((new \ReflectionClass($obj))->getShortName());
    }


    /**
     * Retourne null si la valeur est vide, sinon retourne la valeur
     * 
     * @return object null ou la valeur
     */
    static function nullIfEmpty($value) {
        return empty($value) ? null : $value;
    }

    
    /**
     * Construit la condition WHERE pour les cle primaire
     * 
     * @param object l'objet DTO a lier
     * @param array les nom des cles primaire
     */
    static function buildClause($obj, $clause = null) {
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
            $obj,
            $pr[1]);
    }

}

?>