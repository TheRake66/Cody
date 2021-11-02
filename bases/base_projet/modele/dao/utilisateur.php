<?php

namespace Modele\dao;
use Librairie\DataBase;
use Modele\dto\Utilisateur as dto;



class Utilisateur {

    /**
     * Recupere un objet Utilisateur via un login
     * 
     * @param string le login
     * @return Utilisateur l'utilisateur
     */
    public static function utilisateur($login) {
        return DataBase::fetchObjet(
            "SELECT * 
            FROM utilisateur 
            WHERE identifiant = ?",
            new dto(),
            [ $login ]);
    }


    /**
     * Recupere un objet Utilisateur via son jeton
     * 
     * @param string le jeton
     * @return Utilisateur l'utilisateur
     */
    public static function autoConnexion($jeton) {
        return DataBase::fetchObjet(
            "SELECT * 
			FROM utilisateur AS u
            LEFT JOIN jeton AS j ON u.id = j.id
            WHERE valeur = ?",
            new dto(),
            [ $jeton ]);
    }


    /**
     * Verifi si le mot de passe est correct
     * 
     * @param string le login
     * @param string le mot de passe
     * @return bool vrai ou faux
     */
    public static function verification($login, $pass) {
        return !empty(DataBase::fetchObjet(
            "SELECT * 
			FROM utilisateur 
			WHERE identifiant = ? 
			AND motDePasse = ?",
            new dto(),
            [ $login , $pass ]));
    }


    /**
     * Recupere le sel d'un utilisateur
     * 
     * @param string le login
     * @return string le sel
     */
    public static function sel($login) {
        return DataBase::fetchCell(
            "SELECT sel 
			FROM utilisateur 
			WHERE identifiant = ?",
            [ $login ]);
    }


    /**
     * Verifi si un utilisateur existe
     * 
     * @param string le login
     * @return bool vrai ou faux
     */
    public static function existe($login) {
        return !empty(DataBase::fetchCell(
            "SELECT * 
			FROM utilisateur 
			WHERE identifiant = ?",
            [ $login ]));
    }

}

?>