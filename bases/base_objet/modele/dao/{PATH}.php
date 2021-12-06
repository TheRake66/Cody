<?php
// Classe DAO {NAME_UPPER}
namespace Modele\dao{NAMESPACE_SLASH};
use Librairie\DataBase;
use Modele\dto{NAMESPACE_SLASH}\{NAME_UPPER} as dto;



class {NAME_UPPER} {

    /**
     * Recupere tous les objets {NAME_UPPER}
     * 
     * @return array les objets
     */
    public static function {NAME_LOWER}s() {
        return DataBase::fetchObjets(
			"SELECT * 
			FROM {NAME_LOWER}",
            new dto);
    }


    /**
     * Recupere un objet {NAME_UPPER} via son id
     * 
     * @param string l'id
     * @return {NAME_UPPER} l'objet
     */
    public static function {NAME_LOWER}($id) {
        return DataBase::fetchObjet(
			"SELECT * 
			FROM {NAME_LOWER} 
			WHERE id{NAME_UPPER} = ?",
            new dto,
            [ $id ]);
    }

}

?>