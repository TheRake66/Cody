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

}

?>