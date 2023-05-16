<?php

namespace Kernel\Database;

use PDO;



/**
 * Librairie les transactions SQL.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Transaction {

    /**
     * Démarre une transaction SQL.
     * 
     * @param string $name Le nom de la base de données.
     * @return bool True si la transaction a été démarrée, false sinon.
     */
    static function begin($name = null) {
        $conf = Statement::configuration($name)->options;
        $instance = Statement::instance($name);
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $instance->beginTransaction();
    }


    /**
     * Annule une transaction SQL.
     * 
     * @param string $name Le nom de la base de données.
     * @return bool True si la transaction a été annulée, false sinon.
     */
    static function rollback($name = null) {
        $conf = Statement::configuration($name)->options;
        $instance = Statement::instance($name);
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        return $instance->rollBack();
    }


    /**
     * Valide une transaction SQL.
     * 
     * @param string $name Le nom de la base de données.
     * @return bool True si la transaction a été validée, false sinon.
     */
    static function commit($name = null) {
        $conf = Statement::configuration($name)->options;
        $instance = Statement::instance($name);
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        return $instance->commit();
    }


    /**
     * Vérifie si une transaction SQL est en cours.
     * 
     * @return bool True si une transaction est en cours, false sinon.
     */
    static function has() {
        return Statement::instance()->inTransaction();
    }

}

?>