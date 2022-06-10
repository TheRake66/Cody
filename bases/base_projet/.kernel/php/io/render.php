<?php
namespace Kernel\IO;

use Kernel\Debug\Error;
use Kernel\Html\Import;
use Kernel\Html\Javascript;
use Kernel\Html\Less;
use Kernel\Html\Output;
use Kernel\Io\Convert\Dataset;
use Kernel\Io\Path;


/**
 * Librairie gérant le rendu des composants.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
 */
abstract class Render {

    /**
     * Inclut les fichiers pour afficher le composant.
     * 
     * @example
     *  $this->view('machin') ---> $_0 = 'machin'
     *  $this->view([ 'machin', 'truc' ]) ---> $_0 = 'machin', $_1 = 'truc'
     *  $this->view([ 
     *      'test1' => 'machin', 
     *      'test2' => 'truc'
     * ]) ---> $test1 = 'machin', $test2 = 'truc'
     * 
     * @param array $variables Les variables à injecter dans la vue.
     * @return void
     * @throws Error Si le la vue n'existe pas ou n'est pas lisible.
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
            // On fait directement un require et pas un File::require
            // Pour que le extract fonctionne
            $absolute = Path::absolute($vue);
            require_once($absolute);
            Output::add(Less::import($style));
            Output::add(Javascript::import($script, 'module', $varname, $class));
        } else {
            Error::trigger('Impossible de faire le rendu du composant "' . $full . '" !');
        }
    }
    
}

?>