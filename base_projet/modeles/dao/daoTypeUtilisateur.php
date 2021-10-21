<?php

namespace Modele\dao;
use Librairie\MySQL;
use Modele\dto\TypeUtilisateur as dto;



class TypeUtilisateur {

    /**
     * Recupere le libelle d'un statut par son code
     * 
     * @param string le code
     * @return string le libelle
     */
    public static function statusByCode($unCode) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT libelle 
			FROM type_utilisateur 
			WHERE codetypeutilisateur = :unCode");
        $requetePrepa->bindParam(":unCode", $unCode);
        $requetePrepa->execute();
        
        return $requetePrepa->fetch()[0];
    }
    
}

?>