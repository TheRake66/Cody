<?php
namespace Kernel\Database;
use PDO;



/**
 * Librairie les transactions SQL
 */
class Transaction {

    /**
     * Demarre une transaction SQL
     * 
     * @param bool faux si une erreur est survenue
     */
    static function start() {
        $conf = Statement::getConfiguration();
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::getInstance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return Statement::getInstance()->beginTransaction();
    }


    /**
     * Annule une transaction SQL
     * 
     * @param bool faux si une erreur est survenue
     */
    static function rollback() {
        $conf = Statement::getConfiguration();
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::getInstance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        return Statement::getInstance()->rollBack();
    }


    /**
     * Valide une transaction SQL
     * 
     * @param bool faux si une erreur est survenue
     */
    static function commit() {
        $conf = Statement::getConfiguration();
        if (!$conf->throw_sql_error && $conf->throw_transaction) {
            Statement::getInstance()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        return Statement::getInstance()->commit();
    }


    /**
     * Verifi si une transaction SQL est en cours
     * 
     * @param bool vrai si une transaction est en cours sinon faux
     */
    static function hasStarted() {
        return Statement::getInstance()->inTransaction();
    }

}

?>