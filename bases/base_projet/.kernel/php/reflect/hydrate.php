<?php
namespace Kernel\Reflect;
use Kernel\Debug;



// Trait Hydrate
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
        if (!is_null($donnees) && !empty($donnees)) {
            foreach ($donnees as $key => $value) {
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                } elseif (property_exists($this, $key)) {
                    $this->$key = $value;
                } else {
                    Debug::log('Aucune affectation possible pour le champ ' . $key . ' avec "' . $value . '".', Debug::LEVEL_WARN);
                }
            }
        }
    }
    
}

?>