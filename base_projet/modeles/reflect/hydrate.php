<?php

namespace Modele\Reflect;



trait Hydrate {
    
    /**
     * Hydrate un objet en appelant un setter ou en affectant
     * directement la propriete.
     * Exemple : Une table utilisateur avec comme propriete id
     * hydratera un objet de type dtoUtilisateur en :
     *    - appelant setId($valeur);
     *    - affectant $id = $valeur;
     * 
     * @param array Resultat d'un fetchAll
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
    
}

?>