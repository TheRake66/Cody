<?php
namespace Kernel\Database;

use Kernel\Security\Configuration;
use Kernel\Debug\Error;
use Kernel\Debug\Log;
use PDO;



/**
 * Librairie de connexion a la base de données.
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
     * @var array Instance PDO [string => \PDO].
     */
    private static $instances;
    

    /**
     * @var string La base de données actuelle.
     */
    private static $current;
    

    /**
     * Crée une connexion à la base de données.
     *
     * @param object $conf La configuration de la base de données.
     * @return PDOStatement La connexion à la base de données.
     * @throws Error Si la connexion à la base de données échoue.
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
            PDO::ATTR_PERSISTENT => $conf->options->persistent_mode,
            PDO::ATTR_EMULATE_PREPARES => $conf->options->emulate_prepare,
            PDO::ATTR_ERRMODE => $conf->options->throw_sql_error ?
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
     * Retourne la configuration de la base de données actuelles.
     * 
     * @return object La configuration de la base de données actuelles.
     * @throws Error Si la configuration de la base de données actuelles n'est pas définie dans le fichier de configuration.
     */
    static function configuration() {
        $conf = Configuration::get()->database;
        if (!is_null(self::$current)) {
            foreach ($conf->list as $database) {
                if ($database->name == self::$current) {
                    return $database;
                }
            }
            Error::trigger('Aucune configuration pour la base de données "' . self::$current . '" !');
        } else {
            foreach ($conf->list as $database) {
                if ($database->name == $conf->default) {
                    return $database;
                }
            }
            Error::trigger('Aucune configuration pour la base de données par défaut !');
        }
    }


    /**
     * Retourne l'instance PDO en cours, si aucune n'est en cours en créer une.
     * 
     * @return PDOStatement L'instance PDO en cours.
     * @throws Error Si la connexion à la base de données échoue.
     */
    static function instance() {
        $conf = Configuration::get()->database;
        if (is_null(self::$current)) {
            self::$current = $conf->default;
        }
        if (is_null(self::$instances)) {
            self::$instances = [];
        }
        if (array_key_exists(self::$current, self::$instances)) {
            return self::$instances[self::$current];
        } else {
            if ($conf->progressive) {
                self::$instances[self::$current] = self::connect(self::configuration());
                return self::$instances[self::$current];
            } else {
                foreach ($conf->list as $database) {
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
     * Définit où retourne le nom de la base de données en cours.
     * 
     * @return string|null $name Le nom de la base de données.
     * @return string|null Le nom de la base de données. 
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