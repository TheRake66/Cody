<?php

// ####################################################################################################
class DBConnex extends PDO{
    
    // -------------------------------------------------------
    static $instance;
    // -------------------------------------------------------
    


    // -------------------------------------------------------
    /*
    Retourne l'inctance PDO en cours, si aucune est
    en cours on en creer une
    */
    public static function getInstance() {
        if (!self::$instance ) {
            self::$instance = new DBConnex();
        }
        return self::$instance;
    }
    // -------------------------------------------------------
    

    
    // -------------------------------------------------------
    // Creer une instance PDO
    function __construct() {
        try {
            parent::__construct(Param::BDD_DSN ,Param::BDD_USER, Param::BDD_PASSWORD);
        } catch (Exception $e) {
            echo $e->getMessage();
            die("Impossible de se connecter." );
        }
    }
    // -------------------------------------------------------

}
// ####################################################################################################
