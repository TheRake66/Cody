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
        return DataBase::fetchCell(
            "SELECT valeur 
			FROM jeton 
			WHERE utilisateurId = ?",
            [ $id ]);
    }


    /**
     * Recupere la date d'expiration d'un jeton
     * 
     * @param string le jeton
     * @return string la date
     */
    public static function getDateExpire($jeton) {
        return DataBase::fetchCell(
            "SELECT dateExpiration 
			FROM jeton 
			WHERE valeur = ?",
            [ $jeton ]);
    }
    

    /**
     * Change la date d'expiration d'un jeton
     * 
     * @param string le jeton
     * @return bool vrai ou faux
     */
    public static function setDateExpire($jeton) {
        $date = new \DateTime('now');
        $date->add(new \DateInterval('P31D'));
        $date = $date->format('Y-m-d');

        return DataBase::execute(
            "UPDATE jeton
            SET dateExpiration = ?
			WHERE valeur = ?",
            [ $date , $jeton]);
    }
    

    /**
     * Change la date d'expiration et la valeur d'un jeton
     * 
     * @param string le jeton
     * @param string le nouveau jeton
     * @return bool vrai ou faux
     */
    public static function updateJeton($jeton, $new) {
        $date = new \DateTime('now');
        $date->add(new \DateInterval('P31D'));
        $date = $date->format('Y-m-d');

        return DataBase::execute(
            "UPDATE jeton
            SET dateExpiration = ?, valeur = :new
			WHERE valeur = :jeton",
            [ $date , $new, $jeton ]);
    }
    
}

?>