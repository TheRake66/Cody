<?php

namespace Modele\dao;
use Librairie\MySQL;
use Modele\dto\Jeton as dto;



class Jeton {

    /**
     * Recupere le jeton d'un utilisateur
     * 
     * @param int l'id utilisateur
     * @return string le jeton
     */
    public static function getValeurById($numutilisateur) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT valeur 
			FROM jeton 
			WHERE numutilisateur = :numutilisateur");
        $requetePrepa->bindParam(":numutilisateur", $numutilisateur);
        $requetePrepa->execute();
      
        return $requetePrepa->fetch()[0];
    }


    /**
     * Recupere la date d'expiration d'un jeton
     * 
     * @param string le jeton
     * @return string la date
     */
    public static function getDateExpire($jeton) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT dateexpiration 
			FROM jeton 
			WHERE valeur = :jeton");
        $requetePrepa->bindParam(":jeton", $jeton);
        $requetePrepa->execute();
      
        return $requetePrepa->fetch()[0];
    }
    

    /**
     * Change la date d'expiration d'un jeton
     * 
     * @param string le jeton
     * @return bool vrai ou faux
     */
    public static function setDateExpire($jeton) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"UPDATE jeton
            SET dateexpiration = :dateexp
			WHERE valeur = :jeton");
        $requetePrepa->bindParam(":jeton", $jeton);

        $dateexp = new \DateTime('now');
        $dateexp->add(new \DateInterval('P31D'));
        $dateexp = $dateexp->format('Y-m-d');

        $requetePrepa->bindParam(":dateexp", $dateexp);
        
        return $requetePrepa->execute();
    }
    

    /**
     * Change la date d'expiration et la valeur d'un jeton
     * 
     * @param string le jeton
     * @param string le nouveau jeton
     * @return bool vrai ou faux
     */
    public static function updateJeton($jeton, $newjeton) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"UPDATE jeton
            SET dateexpiration = :dateexp, valeur = :newjeton
			WHERE valeur = :jeton");
        $requetePrepa->bindParam(":jeton", $jeton);
        $requetePrepa->bindParam(":newjeton", $newjeton);

        $dateexp = new \DateTime('now');
        $dateexp->add(new \DateInterval('P31D'));
        $dateexp = $dateexp->format('Y-m-d');

        $requetePrepa->bindParam(":dateexp", $dateexp);
        
        return $requetePrepa->execute();
    }
    
}

?>