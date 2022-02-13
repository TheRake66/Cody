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
        $cont = $folder . 'vue.' . $name . '.php';
        $style = $folder . 'style.' . $name . '.less';
        $script = $folder . 'script.' . $name . '.js';

        if (!is_file($cont) && !is_readable($cont)) {
            $cont = str_replace('_', ' ', $cont);
            $style = str_replace('_', ' ', $style);
            $script = str_replace('_', ' ', $script);
        }
        if (is_file($cont) && is_readable($cont)) {
            include $cont ;
            // Inclut le style
            echo Html::importStyle($style);
            // Inclut et initialise le script
            echo Html::importScript($script, $class);
        } else {
            throw new \Exception('Impossible de faire le rendu de : "' . $full . '".');
        }

    }
    
}

?>