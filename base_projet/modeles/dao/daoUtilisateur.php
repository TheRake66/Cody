<?php

namespace Modele\dao;
use Librairie\MySQL;
use Modele\dto\Utilisateur as dto;



class Utilisateur {

    /**
     * Recupere un objet Utilisateur via un login
     * 
     * @param string le login
     * @return Utilisateur l'utilisateur
     */
    public static function utilisateur($login) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login");
        $requetePrepa->bindParam(":login", $login);
        $requetePrepa->execute();
        
        $liste = $requetePrepa->fetch();

        if(!empty($liste)){
            $unUtilisateur = new dto();
            $unUtilisateur->hydrate($liste);
            return $unUtilisateur;
        }
    }


    /**
     * Recupere un objet Utilisateur via son jeton
     * 
     * @param string le jeton
     * @return Utilisateur l'utilisateur
     */
    public static function autoConnexion($jeton) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
            AND j.valeur = :jeton");
        $requetePrepa->bindParam(":jeton", $jeton);
        $requetePrepa->execute();
        
        $liste = $requetePrepa->fetch();

        if(!empty($liste)){
            $unUtilisateur = new dto();
            $unUtilisateur->hydrate($liste);
            return $unUtilisateur;
        }
    }


    /**
     * Verifi si le mot de passe est correct
     * 
     * @param string le login
     * @param string le mot de passe
     * @return bool vrai ou faux
     */
    public static function verification($unLogin, $unMdp) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login 
			AND motdepasse = :mdp");
        $requetePrepa->bindParam(":login", $unLogin);
        $requetePrepa->bindParam(":mdp", $unMdp);
        $requetePrepa->execute();

        if (empty($requetePrepa->fetch(\PDO::FETCH_ASSOC))) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Recupere le sel d'un utilisateur
     * 
     * @param string le login
     * @return string le sel
     */
    public static function sel($unLogin) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT sel 
			FROM utilisateur 
			WHERE identifiant = :login");
        $requetePrepa->bindParam(":login", $unLogin);
        $requetePrepa->execute();
      
        return $requetePrepa->fetch()[0];
    }


    /**
     * Verifi si un utilisateur existe
     * 
     * @param string le login
     * @return bool vrai ou faux
     */
    public static function existe($unLogin) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login");
        $requetePrepa->bindParam(":login", $unLogin);
        $requetePrepa->execute();
        
        return !empty($requetePrepa->fetch()[0]);
    }

}

?>