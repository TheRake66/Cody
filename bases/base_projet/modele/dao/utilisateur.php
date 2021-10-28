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
        $rqt = DataBase::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login");
        $rqt->bindParam(":login", $login);
        $rqt->execute();
        
        $liste = $rqt->fetch();

        if (!empty($liste)) {
            $user = new dto();
            $user->hydrate($liste);
            return $user;
        }
    }


    /**
     * Recupere un objet Utilisateur via son jeton
     * 
     * @param string le jeton
     * @return Utilisateur l'utilisateur
     */
    public static function autoConnexion($jeton) {
        $rqt = DataBase::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur AS u
            LEFT JOIN jeton AS j ON u.id = j.id
            WHERE valeur = :jeton");
        $rqt->bindParam(":jeton", $jeton);
        $rqt->execute();
        
        $liste = $rqt->fetch();

        if (!empty($liste)) {
            $user = new dto();
            $user->hydrate($liste);
            return $user;
        }
    }


    /**
     * Verifi si le mot de passe est correct
     * 
     * @param string le login
     * @param string le mot de passe
     * @return bool vrai ou faux
     */
    public static function verification($login, $pass) {
        $rqt = DataBase::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login 
			AND motDePasse = :pass");
        $rqt->bindParam(":login", $login);
        $rqt->bindParam(":pass", $pass);
        $rqt->execute();

        return !empty($rqt->fetch(\PDO::FETCH_ASSOC));
    }


    /**
     * Recupere le sel d'un utilisateur
     * 
     * @param string le login
     * @return string le sel
     */
    public static function sel($login) {
        $rqt = DataBase::getInstance()->prepare(
			"SELECT sel 
			FROM utilisateur 
			WHERE identifiant = :login");
        $rqt->bindParam(":login", $login);
        $rqt->execute();
      
        return $rqt->fetch()[0];
    }


    /**
     * Verifi si un utilisateur existe
     * 
     * @param string le login
     * @return bool vrai ou faux
     */
    public static function existe($login) {
        $rqt = DataBase::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login");
        $rqt->bindParam(":login", $login);
        $rqt->execute();
        
        return !empty($rqt->fetch()[0]);
    }

}

?>