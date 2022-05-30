<?php
namespace Kernel\Database;

use PDO;



/**
 * Librairie les transactions SQL
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
     * Demarre une transaction SQL
     * 
     * @param bool faux si une erreur est survenue
     */
    static function begin() {
        $conf = Statement::configuration();
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::instance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return Statement::instance()->beginTransaction();
    }


    /**
     * Annule une transaction SQL
     * 
     * @param bool faux si une erreur est survenue
     */
    static function rollback() {
        $conf = Statement::configuration();
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::instance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        return Statement::instance()->rollBack();
    }


    /**
     * Valide une transaction SQL
     * 
     * @param bool faux si une erreur est survenue
     */
    static function commit() {
        $conf = Statement::configuration();
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::instance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        return Statement::instance()->commit();
    }


    /**
     * Verifi si une transaction SQL est en cours
     * 
     * @param bool vrai si une transaction est en cours sinon faux
     */
    static function has() {
        return Statement::instance()->inTransaction();
    }

}

?>