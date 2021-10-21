<?php

namespace Modele\dao;
use Librairie\MySQL;
use Modele\dto\{NAME_UPPER} as dto;



class {NAME_UPPER} {

    /**
     * Recupere un objet {NAME_UPPER} via un id
     * 
     * @param string l'id{NAME_UPPER}
     * @return {NAME_UPPER} le {NAME_LOWER}
     */
    public static function {NAME_LOWER}($id{NAME_UPPER}) {
        $requetePrepa = MySQL::getInstance()->prepare(
			"SELECT * 
			FROM {NAME_LOWER} 
			WHERE id{NAME_UPPER} = :id{NAME_UPPER}");
        $requetePrepa->bindParam(":id{NAME_UPPER}", $id{NAME_UPPER});
        $requetePrepa->execute();
        
        $liste = $requetePrepa->fetch();

        if(!empty($liste)){
            $un{NAME_UPPER} = new dto();
            $un{NAME_UPPER}->hydrate($liste);
            return $un{NAME_UPPER};
        }
    }

}

?>