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
class Transaction {

    /**
     * Demarre une transaction SQL
     * 
     * @param bool faux si une erreur est survenue
     */
    static function begin() {
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
    static function hasBegun() {
        return Statement::getInstance()->inTransaction();
    }

}

?>