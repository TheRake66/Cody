<?php
// Librairie Error
namespace Kernel;



class Error {

    /**
     * Initialise les messages d'erreur
     */
    static function handler() {
        set_error_handler('Kernel\Error::showError');
        register_shutdown_function('Kernel\Error::showFatal');
    }


    /**
     * Recupere et affiche un message d'erreur fatal
     */
    static function showFatal() {
        $error = error_get_last();
        if($error !== NULL) {
            $severity   = $error["type"];
            $filename = $error["file"];
            $lineno = $error["line"];
            $message  = $error["message"];
        } else {            
            $filename = "Inconnu";
            $message  = "Aucun";
            $severity   = E_CORE_ERROR;
            $lineno = -1;
        }
        self::showError($severity, $message, $filename, $lineno);
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