<?php
namespace Kernel;
use Kernel\Html;



/**
 * Librairie gerant le rendu des vues
 */
class Render {

    /**
     * Inclut les fichiers pour afficher la vue
     * 
     * @param array les variables a passer a la vue
     * @return void
     * @throws \Exception Si le fichier de vue n'est pas trouvé
     */
    protected function view($variables = []) {
        // Recupere le namespace\class
        $full = get_class($this);

        // Coupe le namespace et de la class
        $explode = explode('\\', $full);
        $class = end($explode);
        $namespace = array_slice($explode, 1, count($explode) - 1);
        
        // Construit les nom de fichier et le dossier
        $folder = 'debug/app/' . strtolower(implode('/', $namespace)) . '/';
        $varname = strtolower(implode('_', $namespace));
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
            include $cont;
            // Inclut le style
            echo Html::importStyle($style);
            // Inclut et initialise le script
            echo Html::importScript($script, 'module', $varname, $class);
        } else {
            trigger_error('Impossible de faire le rendu de : "' . $full . '" !');
        }

    }
    
}

?>