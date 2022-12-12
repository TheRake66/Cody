<?php
namespace Kernel\Database;

use PDO;



/**
 * Librairie les transactions SQL.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Transaction {

    /**
     * Démarre une transaction SQL.
     * 
     * @param bool True si la transaction a été démarrée, false sinon.
     */
    static function begin() {
        $conf = Statement::configuration()->options;
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::instance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return Statement::instance()->beginTransaction();
    }


    /**
     * Annule une transaction SQL.
     * 
     * @param bool True si la transaction a été annulée, false sinon.
     */
    static function rollback() {
        $conf = Statement::configuration()->options;
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::instance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        return Statement::instance()->rollBack();
    }


    /**
     * Valide une transaction SQL.
     * 
     * @param bool True si la transaction a été validée, false sinon.
     */
    static function commit() {
        $conf = Statement::configuration()->options;
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::instance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        return Statement::instance()->commit();
    }


    /**
     * Vérifie si une transaction SQL est en cours.
     * 
     * @param bool True si une transaction est en cours, false sinon.
     */
    static function has() {
        return Statement::instance()->inTransaction();
    }

}

?>