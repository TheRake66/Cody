<?php

// ####################################################################################################
class daoTypeUtilisateur {

    // -------------------------------------------------------
    public static function statusByCode($unCode) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT libelle 
			FROM type_utilisateur 
			WHERE codetypeutilisateur = :unCode");
        $requetePrepa->bindParam(":unCode", $unCode);
        $requetePrepa->execute();
        
        return $requetePrepa->fetch()[0];
    }
    // -------------------------------------------------------
    
}
// ####################################################################################################