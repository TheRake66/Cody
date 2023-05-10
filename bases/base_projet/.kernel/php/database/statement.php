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
 * @version 1.0.0.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Statement {
    
    /**
     * @var array Instance PDO [string => \PDO].
     */
    private static $instances = [];
    

    /**
     * @var string La base de données actuelle.
     */
    private static $current = null;
    

    /**
     * Crée une connexion à la base de données.
     *
     * @param object $conf La configuration de la base de données.
     * @return PDOStatement La connexion à la base de données.
     * @throws Error Si la connexion à la base de données échoue.
     */
    private static function connect($conf) {
        Log::progress('Connexion à la base de données "' . $conf->name . '"...');
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
        Log::good('Connexion réussite.');
        return $pdo;
    }


    /**
     * Récupère le nom de la base de donnée par défaut.
     * 
     * @return void
     */
    static function init() {
        $conf = Configuration::get()->database;
        $name = $conf->default;
        self::$current = $name;
        Log::add('Définition de base de données par défaut par "' . $name .'".');
    }


    /**
     * Retourne la configuration de la base de données demandée, sinon retourne la configuration de la base de données actuelle, 
     * sinon retourne la configuration de la base de données par défaut.
     * 
     * @return object La configuration de la base de données actuelles.
     * @throws Error Si la configuration de la base de données actuelles n'est pas définie dans le fichier de configuration.
     */
    static function configuration($name = null) {
        if (is_null($name)) {
            $name = self::$current;
        }
        $conf = Configuration::get()->database;
        foreach ($conf->list as $database) {
            if ($database->name == $name) {
                return $database;
            }
        }
        Error::trigger('Aucune configuration pour la base de données "' . self::$current . '" !');
    }


    /**
     * Retourne l'instance PDO demandée, si null, retourne l'instance PDO en cours. Si aucune n'est en cours en créer une.
     * 
     * @param string|null $name Le nom de la base de données.
     * @return PDOStatement L'instance PDO en cours.
     * @throws Error Si la connexion à la base de données échoue.
     */
    static function instance($name = null) {
        if (is_null($name)) {
            $name = self::$current;
        }
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        } else {
            $conf = Configuration::get()->database;
            if (!$conf->progressive) {
                foreach ($conf->list as $database) {
                    $newName = $database->name;
                    self::$instances[$newName] = self::connect($database); 
                } 
            } else {
                $newConf = self::configuration($name);
                self::$instances[$name] = self::connect($newConf);
            }
            return self::$instances[$name];
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
            Log::add('Changement de base de données vers "' . $name .'".');
        }
    }

}

?>