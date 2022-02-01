<?php
// Librairie Render
namespace Kernel;



class Render {

    /**
     * Inclut les fichiers pour afficher la vue
     * 
     * @param array les variables a passer a la vue
     */
	function view($variables = []) {
		$full = get_class($this);

        $explode = explode('\\', $full);
        $class = end($explode);
        $namespace = array_slice($explode, 1, count($explode) - 1);
        
        $folder = 'composant/' . strtolower(implode('/', $namespace)) . '/';
        $name = strtolower($class);
        
        extract($variables);

        include $folder . 'vue.' . $name . '.php';
        echo '<link rel="stylesheet/less" type="text/css" href="' . $folder . 'style.' . $name . '.less">';
        echo '<script type="text/javascript" src="' . $folder . 'script.' . $name . '.js"></script>';
	}
	
}

?>