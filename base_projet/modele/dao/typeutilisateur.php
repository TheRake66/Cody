<?php

namespace Modele\dao;
use Librairie\DataBase;
use Modele\dto\Type as dto;



class TypeUtilisateur {

    /**
     * Recupere le libelle d'un statut par son code
     * 
     * @param string le code
     * @return string le libelle
     */
    public static function statusByCode($code) {
        $rqt = DataBase::getInstance()->prepare(
			"SELECT libelle 
			FROM codeTypeUtilisateur
			WHERE codeTypeUtilisateur = :code");
        $rqt->bindParam(":code", $code);
        $rqt->execute();
        
        return $rqt->fetch()[0];
    }
    
}

?>