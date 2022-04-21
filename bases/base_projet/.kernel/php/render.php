<?php
namespace Kernel;
use Kernel\Html;



/**
 * Librairie gerant le rendu composants
 */
class Render {

    /**
     * Inclut les fichiers pour afficher le composant
     * 
     * @param object le controleur en lien avec le composant
     * @param array les variables a passer a la vue au format cle => valeur
     * @return void
     * @throws Error si les fichiers (vue, style, script) n'existent pas ou ne sont pas lisible
     */
    static function view($controler = null, $variables = null) {
        // Recupere le namespace\class du controlleur
        $full = is_null($controler) ? 
            debug_backtrace()[1]['class'] : 
            get_class($controler);

        // Coupe le namespace et de la class
        $explode = explode('\\', $full);
        $class = end($explode);
        $namespace = array_slice($explode, 1, count($explode) - 1);
        
        // Construit les noms de fichier et le dossier
        $folder = 'debug/app/' . strtolower(implode('/', $namespace)) . '/';
        $varname = strtolower(implode('_', $namespace));
        $name = strtolower($class);
        
        // Envoi les variables a la vue
        if (!is_null($variables)) {
            if (is_array($variables)) {
                if (Convert::isAssoc($variables)) {
                    extract($variables);
                } else {
                    $_ = [];
                    for ($i = 0; $i < count($variables); $i++) {
                        $_['_'.$i] = $variables[$i];
                    }
                    extract($_);
                }
            } else {
                extract([ '_0' => $variables ]);
            }
        }
        
        // Inclut la vue
        $vue = $folder . 'vue.' . $name . '.php';
        $style = $folder . 'style.' . $name . '.less';
        $script = $folder . 'script.' . $name . '.js';

        if (!is_file($vue) || !is_readable($vue)) {
            $vue = str_replace('_', ' ', $vue);
            $style = str_replace('_', ' ', $style);
            $script = str_replace('_', ' ', $script);
        }
        
        if (is_file($vue) && is_readable($vue)) {
            require $vue;
            // Inclut le style
            Html::add(Html::importStyle($style));
            // Inclut et initialise le script
            Html::add(Html::importScript($script, 'module', $varname, $class));
        } else {
            Error::trigger('Impossible de faire le rendu du composant "' . $full . '" !');
        }
    }
    
}

?>