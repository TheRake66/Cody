<?php

namespace Librairie;



class MySQL extends \PDO {
    
    /**
     * Instance PDO
     */
    static $instance;

    /**
     * DSN de l'instance
     */
    static $dsn;

    /**
     * Identifiant de login a la BDD
     */
    static $login;

    /**
     * Mot de passe de login a la BDD
     */
    static $password;
    


    /**
     * Configure la connexion MySQL
     */
    static function configure($login, $pass, $host, $name) {
        self::$login = $login;
        self::$password = $pass;
        self::$dsn = 'mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8';
    }


    /**
     * Retourne l'inctance PDO en cours, si aucune est
     * en cours on en creer une
     */
    static function getInstance() {
        if (!self::$instance) {
            self::$instance = new MySQL();
        }
        return self::$instance;
    }
    
    
    /**
     * Creer une instance PDO
     */
    function __construct() {
        try {
            parent::__construct(self::$dsn, self::$login, self::$password);
        } catch (\Exception $e) {
            echo $e->getMessage();
            die("Impossible de se connecter.");
        }
    }

}

?>