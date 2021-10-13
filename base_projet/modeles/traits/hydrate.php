<?php

// ####################################################################################################
trait Hydrate {

    // -------------------------------------------------------
    /*
    Hydrate un objet en appelant un setter ou en affectant
    directement la propriete.
    Exemple : Une table utilisateur avec comme propriete IdUtilisateur
    hydratera un objet de type dtoUtilisateur en :
        - appelant setIdUtilisateur($valeur);
        - affectant $IdUtilisateur = $valeur;
    */
    function hydrate($donnees) {        
        foreach ($donnees as $key => $value) {
            $method = 'set'.ucfirst($key);
                     
            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    // -------------------------------------------------------

}
// ####################################################################################################
