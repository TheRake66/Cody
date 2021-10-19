<?php

// ####################################################################################################
// Enregistre l'autoloader
spl_autoload_register('Autoloader::autoloadAllsPath');
// ####################################################################################################





// ####################################################################################################
class Autoloader{

    // -------------------------------------------------------
    // Chemins des dossiers dans lequels l'autoloader
    // va chercher les fichier a inclure
	const PATHS = [
        'modeles/dto',
        'modeles/dao',
        'modeles/traits',
        'librairies',
    ];
    // -------------------------------------------------------
    


    // -------------------------------------------------------
    // Cherche les fichiers a inclure
    static function autoloadAllsPath($class){
        foreach (Autoloader::PATHS as $path) {
            $file = "{$path}/{$class}.php";
            if(is_file($file) && is_readable($file)) {
                require_once $file;
                break;
            }
        }
    }
    // -------------------------------------------------------
    
}
// ####################################################################################################


