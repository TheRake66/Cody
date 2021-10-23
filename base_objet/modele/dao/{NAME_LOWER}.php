<?php

namespace Modele\dao{NAMESPACE};
use Librairie\DataBase;
use Modele\dto{NAMESPACE}\{NAME_UPPER} as dto;



class {NAME_UPPER} {

    /**
     * Recupere un objet {NAME_UPPER} via son id
     * 
     * @param string le login
     * @return {NAME_UPPER}  le {NAME_LOWER}
     */
    public static function {NAME_LOWER} ($login) {
        $rqt = DataBase::getInstance()->prepare(
			"SELECT * 
			FROM {NAME_LOWER} 
			WHERE identifiant = :login");
        $rqt->bindParam(":login", $login);
        $rqt->execute();
        
        $liste = $rqt->fetch();

        if (!empty($liste)) {
            $obj = new dto();
            $obj->hydrate($liste);
            return $obj;
        }
    }

}

?>