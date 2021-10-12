<?php

// ####################################################################################################
class daoUtilisateur {

    // -------------------------------------------------------
    public static function utilisateur($login) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login");
        $requetePrepa->bindParam(":login", $login);
        $requetePrepa->execute();
        
        $liste = $requetePrepa->fetch();

        if(!empty($liste)){
            $unUtilisateur = new dtoUtilisateur();
            $unUtilisateur->hydrate($liste);
            return $unUtilisateur;
        }
    }
    // -------------------------------------------------------



    // -------------------------------------------------------
    public static function autoConnexion($jeton) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
            AND j.valeur = :jeton");
        $requetePrepa->bindParam(":jeton", $jeton);
        $requetePrepa->execute();
        
        $liste = $requetePrepa->fetch();

        if(!empty($liste)){
            $unUtilisateur = new dtoUtilisateur();
            $unUtilisateur->hydrate($liste);
            return $unUtilisateur;
        }
    }
    // -------------------------------------------------------


    
    // -------------------------------------------------------
    public static function verification($unLogin, $unMdp) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login 
			AND motdepasse = :mdp");
        $requetePrepa->bindParam(":login", $unLogin);
        $requetePrepa->bindParam(":mdp", $unMdp);
        $requetePrepa->execute();

        if (empty($requetePrepa->fetch(PDO::FETCH_ASSOC))) {
            return false;
        } else {
            return true;
        }
    }
    // -------------------------------------------------------



    // -------------------------------------------------------
    public static function sel($unLogin) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT sel 
			FROM utilisateur 
			WHERE identifiant = :login");
        $requetePrepa->bindParam(":login", $unLogin);
        $requetePrepa->execute();
      
        return $requetePrepa->fetch()[0];
    }
    // -------------------------------------------------------
    


    // -------------------------------------------------------
    public static function existe($unLogin) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT * 
			FROM utilisateur 
			WHERE identifiant = :login");
        $requetePrepa->bindParam(":login", $unLogin);
        $requetePrepa->execute();
        
        return !empty($requetePrepa->fetch()[0]);
    }
    // -------------------------------------------------------

}
// ####################################################################################################