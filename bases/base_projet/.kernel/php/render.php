<?php
namespace Kernel;
use Kernel\Html\Import;
use Kernel\Html\Output;



/**
 * Librairie gerant le rendu composants
 */
abstract class Render {

    /**
     * Inclut les fichiers pour afficher le composant
     * 
     * @param array les variables a passer a la vue au format cle => valeur
     * @return void
     * @throws Error si les fichiers (vue, style, script) n'existent pas ou ne sont pas lisible
     */
    protected function view($variables = null) {
        $full = get_class($this);
        $explode = explode('\\', $full);
        $class = end($explode);
        $namespace = array_slice($explode, 1, count($explode) - 1);
        $folder = 'debug/app/' . strtolower(implode('/', $namespace)) . '/';
        $varname = strtolower(implode('_', $namespace));
        $name = strtolower($class);
        
        if (!empty($variables)) {
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
        
        $vue = $folder . 'vue.' . $name . '.php';
        $vueabs = Path::absolute($vue);
        $style = $folder . 'style.' . $name . '.less';
        $script = $folder . 'script.' . $name . '.js';
        if (!is_file($vueabs) || !is_readable($vueabs)) {
            $vueabs = Path::absolute(str_replace('_', ' ', $vue));
            $style = str_replace('_', ' ', $style);
            $script = str_replace('_', ' ', $script);
        }
        if (is_file($vueabs) && is_readable($vueabs)) {
            // On fait directement un requiere et pas un Path::require
            // Pour que le extract fonctionne
            require($vueabs);
            Output::add(Import::importStyle($style));
            Output::add(Import::importScript($script, 'module', $varname, $class));
        } else {
            Error::trigger('Impossible de faire le rendu du composant "' . $full . '" !');
        }
    }
    
}

?>