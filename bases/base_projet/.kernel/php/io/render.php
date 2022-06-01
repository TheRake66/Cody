<?php
namespace Kernel\IO;

use Kernel\Debug\Error;
use Kernel\HTML\Import;
use Kernel\HTML\Javascript;
use Kernel\HTML\Less;
use Kernel\HTML\Output;
use Kernel\IO\Convert\Dataset;
use Kernel\IO\Path;


/**
 * Librairie gerant le rendu composants
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
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
                if (Dataset::assoc($variables)) {
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
        
        $vue = $folder . $name . '.phtml';
        $style = $folder . $name . '.less';
        $script = $folder . $name . '.js';
        if (File::loadable($vue)) {
            $vue = str_replace('_', ' ', $vue);
            $style = str_replace('_', ' ', $style);
            $script = str_replace('_', ' ', $script);
        }
        if (File::loadable($vue)) {
            // On fait directement un requiere et pas un Path::require
            // Pour que le extract fonctionne
            File::require($vue);
            Output::add(Less::import($style));
            Output::add(Javascript::import($script, 'module', $varname, $class));
        } else {
            Error::trigger('Impossible de faire le rendu du composant "' . $full . '" !');
        }
    }
    
}

?>