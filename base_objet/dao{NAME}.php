<?php

// ####################################################################################################
class dao{NAME} {
    
    // -------------------------------------------------------
    public static function get{NAME}($id) {
        $requetePrepa = DBConnex::getInstance()->prepare(
			"SELECT * 
			FROM {NAME} 
			WHERE id{NAME} = :id");
        $requetePrepa->bindParam(":id", $id);
        $requetePrepa->execute();
        
        $liste = $requetePrepa->fetch();

        if(!empty($liste)){
            ${NAME} = new dto{NAME}();
            ${NAME}->hydrate($liste);
            return ${NAME};
        }
    }
    // -------------------------------------------------------

}
// ####################################################################################################