<?php
namespace Kernel\Database;

use Kernel\Security\Configuration;
use Kernel\Debug\Error;
use Kernel\Debug\Log;
use PDO;



/**
 * Librairie de connexion a la base de donnees
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Statement {
    
    /**
     * @var array Instance PDO [string => \PDO]
     */
    private static $instances;
    
    /**
     * @var string La base de donnees utilisee
     */
    private static $current;
    

    /**
     * Creer une instance PDO
     *
     * @param array configuration de la base de donnees
     * @return PDOStatement instance PDO
     * @throws Error si la connexion echoue
     */
    private static function connect($conf) {
        Log::add('Connexion à la base de données "' . $conf->name . '"...', Log::LEVEL_PROGRESS);
        $pdo = null;
        $dsn = $conf->type . 
            ':host=' . $conf->host . 
            ';port=' . $conf->port . 
            ';dbname=' . $conf->name . 
            ';charset=' . $conf->encoding;
        $options = [
            PDO::ATTR_PERSISTENT => $conf->persistent_mode,
            PDO::ATTR_EMULATE_PREPARES => $conf->emulate_prepare,
            PDO::ATTR_ERRMODE => $conf->throw_sql_error ?
                    PDO::ERRMODE_EXCEPTION :
                    PDO::ERRMODE_SILENT
        ];
        try {
            $pdo = new PDO($dsn, $conf->login, $conf->password, $options);
        } catch (\Exception $e) {
            Error::trigger('Impossible de se connecter à la base de données "' . $conf->name . '".', $e);
        }
        Log::add('Connexion réussite.', Log::LEVEL_GOOD);
        return $pdo;
    }


    /**
     * Retourne la configuration de la base de donnees actuelle
     * 
     * @return object configuration de la base de donnees
     * @throws Error si la base de donnees par défaut n'est pas définie
     */
    static function configuration() {
        $conf = Configuration::get()->database;
        if (!is_null(self::$current)) {
            foreach ($conf->databases_list as $database) {
                if ($database->name == self::$current) {
                    return $database;
                }
            }
            Error::trigger('Aucune configuration pour la base de données "' . self::$current . '" !');
        } else {
            foreach ($conf->databases_list as $database) {
                if ($database->name == $conf->default_database) {
                    return $database;
                }
            }
            Error::trigger('Aucune configuration pour la base de données par défaut !');
        }
    }


    /**
     * Retourne l'instance PDO en cours, si aucune est en cours on en creer une
     * 
     * @return PDOStatement instance PDO
     * @throws Error si la base de donnees n'est pas definie
     */
    static function instance() {
        $conf = Configuration::get()->database;
        if (is_null(self::$current)) {
            self::$current = $conf->default_database;
        }
        if (is_null(self::$instances)) {
            self::$instances = [];
        }
        if (array_key_exists(self::$current, self::$instances)) {
            return self::$instances[self::$current];
        } else {
            if ($conf->progressive_connection) {
                self::$instances[self::$current] = self::connect(self::configuration());
                return self::$instances[self::$current];
            } else {
                foreach ($conf->databases_list as $database) {
                    self::$instances[$database->name] = self::connect($database);
                } 
                if (array_key_exists(self::$current, self::$instances)) {
                    return self::$instances[self::$current];
                } else {
                    Error::trigger('Aucune configuration pour la base de données "' . self::$current . '" !');
                }
            }
        }
    }


    /**
     * Change ou retourne le nom de la base de donnees en cours
     * 
     * @return string|null nom de la base de donnees
     * @return string nom de la base de donnees
     */
    static function current($name = null) {
        if (is_null($name)) {
            return self::$current;
        } else {
            self::$current = $name;
            Log::add('Changement de base de données vers "' . $name .'".', Log::LEVEL_GOOD);
        }
    }

}

?>