<?php

namespace Librairie;



class Progress {
    
    /**
     * Creer une progress bar ronde
     * 
     * @param double pourcentage
     * @param string couleur
     * @param int taille en diametre
     * @param int taille de la police
     * @param int taille de la bordure
     * @param string unite de style
     */
    public static function addCircleProgress($percent, $color = 'green', $size = 250, $font = 72, $border = 5, $unite = 'px') {
        echo '<div 
        class="div-progress-' . $color . '" 
        style="
        --percent: ' . (110 - $percent) . '%; 
        --font: ' . $font . $unite . '; 
        --size: ' . $size . $unite . ';
        --border: ' . $border . $unite . ';
        "><h1>' . $percent . '%</h1></div>';
    }
    
}

?>