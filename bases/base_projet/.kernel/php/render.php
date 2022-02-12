<?php
namespace Kernel;
use Kernel\Html;



// Librairie Render
class Render {

    /**
     * Inclut les fichiers pour afficher la vue
     * 
     * @param array les variables a passer a la vue
     */
    function view($variables = []) {
        // Recupere le namespace\class
        $full = get_class($this);

        // Coupe le namespace et de la class
        $explode = explode('\\', $full);
        $class = end($explode);
        $namespace = array_slice($explode, 1, count($explode) - 1);
        
        // Construit les nom de fichier et le dossier
        $folder = 'debug/app/' . strtolower(implode('/', $namespace)) . '/';
        $name = strtolower($class);
        
        // Envoi les variables a la vue
        extract($variables);

        // Inclut la vue
        include $folder . 'vue.' . $name . '.php';
        // Inclut le style
        echo Html::importStyle($folder . 'style.' . $name . (Configuration::get()->in_production ? '.min.css' : '.less'));
        // Inclut et initialise le script
        echo Html::importScript($folder . 'script.' . $name . (Configuration::get()->in_production ? '.min.js' : '.js'), $name, $class);
    }
    
}

?>