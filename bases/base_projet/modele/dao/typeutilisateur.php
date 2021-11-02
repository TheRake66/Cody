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
        return DataBase::fetchCell(
            "SELECT libelle 
			FROM codeTypeUtilisateur
			WHERE codeTypeUtilisateur = ?",
            [ $code ]);
    }
    
}

?>