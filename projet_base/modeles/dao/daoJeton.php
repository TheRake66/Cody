<?php

// ####################################################################################################
class daoJeton {

    // -------------------------------------------------------
    public static function getValeurById($numutilisateur) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT valeur 
			FROM jeton 
			WHERE numutilisateur = :numutilisateur");
        $requetePrepa->bindParam(":numutilisateur", $numutilisateur);
        $requetePrepa->execute();
      
        return $requetePrepa->fetch()[0];
    }
    // -------------------------------------------------------



    // -------------------------------------------------------
    public static function getDateExpire($jeton) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT dateexpiration 
			FROM jeton 
			WHERE valeur = :jeton");
        $requetePrepa->bindParam(":jeton", $jeton);
        $requetePrepa->execute();
      
        return $requetePrepa->fetch()[0];
    }
    // -------------------------------------------------------



    // -------------------------------------------------------
    public static function setDateExpire($jeton) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"UPDATE jeton
            SET dateexpiration = :dateexp
			WHERE valeur = :jeton");
        $requetePrepa->bindParam(":jeton", $jeton);

        $dateexp = new DateTime('now');
        $dateexp->add(new DateInterval('P31D'));
        $dateexp = $dateexp->format('Y-m-d');

        $requetePrepa->bindParam(":dateexp", $dateexp);
        
        return $requetePrepa->execute();
    }
    // -------------------------------------------------------

    

    // -------------------------------------------------------
    public static function updateJeton($jeton, $newjeton) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"UPDATE jeton
            SET dateexpiration = :dateexp, valeur = :newjeton
			WHERE valeur = :jeton");
        $requetePrepa->bindParam(":jeton", $jeton);
        $requetePrepa->bindParam(":newjeton", $newjeton);

        $dateexp = new DateTime('now');
        $dateexp->add(new DateInterval('P31D'));
        $dateexp = $dateexp->format('Y-m-d');

        $requetePrepa->bindParam(":dateexp", $dateexp);
        
        return $requetePrepa->execute();
    }
    // -------------------------------------------------------
    
}
// ####################################################################################################