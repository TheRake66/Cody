<?php

namespace Modele\dao;
use Librairie\DataBase;
use Modele\dto\Jeton as dto;



class Jeton {

    /**
     * Recupere le jeton d'un utilisateur
     * 
     * @param int l'id utilisateur
     * @return string le jeton
     */
    public static function getValeurById($id) {
        $rqt = DataBase::getInstance()->prepare(
			"SELECT valeur 
			FROM jeton 
			WHERE utilisateurId = :id");
        $rqt->bindParam(":id", $id);
        $rqt->execute();
      
        return $rqt->fetch()[0];
    }


    /**
     * Recupere la date d'expiration d'un jeton
     * 
     * @param string le jeton
     * @return string la date
     */
    public static function getDateExpire($jeton) {
        $rqt = DataBase::getInstance()->prepare(
			"SELECT dateExpiration 
			FROM jeton 
			WHERE valeur = :jeton");
        $rqt->bindParam(":jeton", $jeton);
        $rqt->execute();
      
        return $rqt->fetch()[0];
    }
    

    /**
     * Change la date d'expiration d'un jeton
     * 
     * @param string le jeton
     * @return bool vrai ou faux
     */
    public static function setDateExpire($jeton) {
        $rqt = DataBase::getInstance()->prepare(
			"UPDATE jeton
            SET dateExpiration = :date
			WHERE valeur = :jeton");
        $rqt->bindParam(":jeton", $jeton);
        $date = new \DateTime('now');
        $date->add(new \DateInterval('P31D'));
        $date = $date->format('Y-m-d');
        $rqt->bindParam(":date", $date);
        
        return $rqt->execute();
    }
    

    /**
     * Change la date d'expiration et la valeur d'un jeton
     * 
     * @param string le jeton
     * @param string le nouveau jeton
     * @return bool vrai ou faux
     */
    public static function updateJeton($jeton, $new) {
        $rqt = DataBase::getInstance()->prepare(
			"UPDATE jeton
            SET dateExpiration = :date, valeur = :new
			WHERE valeur = :jeton");
        $rqt->bindParam(":jeton", $jeton);
        $rqt->bindParam(":new", $new);
        $date = new \DateTime('now');
        $date->add(new \DateInterval('P31D'));
        $date = $date->format('Y-m-d');
        $rqt->bindParam(":date", $date);
        
        return $rqt->execute();
    }
    
}

?>