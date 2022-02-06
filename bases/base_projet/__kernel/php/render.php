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
        // Recupere le namespace\class
		$full = get_class($this);

        // Coupe le namespace et de la class
        $explode = explode('\\', $full);
        $class = end($explode);
        $namespace = array_slice($explode, 1, count($explode) - 1);
        
        // Construit les nom de fichier et le dossier
        $folder = 'src/app/' . strtolower(implode('/', $namespace)) . '/';
        $name = strtolower($class);
        
        // Envoi les variables a la vue
        extract($variables);

        // Inclut la vue
        include $folder . 'vue.' . $name . '.php';
        // Inclut le style
        echo '<link rel="stylesheet/less" type="text/css" href="' . $folder . 'style.' . $name . '.less">';
        // Inclut et initialise le script
        echo '<script type="text/javascript" src="' . $folder . 'script.' . $name . '.js"></script>';
        echo '<script>const ' . $name . ' = new ' . $class .'();</script>';
	}
	
}

?>