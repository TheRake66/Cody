<?php
// Librairie Error
namespace Kernel;



class Error {

    /**
     * Initialise les messages d'erreur
     */
    static function handler() {
        set_error_handler('Kernel::showError');
    }

    /**
     * Affiche un message d'erreur
     * 
     * @param int code erreur
     * @param string le message
     * @param string le fichier concerner
     * @param int le numero de la ligne
     */
    static function showError($severity, $message, $filename, $lineno) {
        if (!Configuration::get()->debogage) {
            echo $message;
        }
    }
    
}

?>