<?php
namespace Kernel;



// Librairie Image
class Image {

    /**
     * Compresse une image avec la taille souhaiter
     * 
     * @param string les donnees de l'image
     * @param int la largeur finale
     * @param int la hauteur finale
     * @return string l'image compressee
     */
    static function compressFromString($data, $width = 128, $height = 128) {
        $source = imagecreatefromstring($data);
        $info = getimagesizefromstring($data);
        $dest = imagecreatetruecolor($width, $height);
        $width_or = imagesx($source);
        $height_or = imagesy($source);

        imagecopyresampled($dest, $source, 0, 0, 0, 0, $width, $height, $width_or, $height_or);
        ob_start();
        switch ($info['mime']) {
            case 'image/jpeg':
                imagejpeg($dest);
                break;
            case 'image/gif':
                imagegif($dest);
                break;
            case 'image/png':
                imagepng($dest);
                break;

        }
        $compress =  ob_get_contents();
        ob_end_clean();

        return $compress;
    }
    

    /**
     * Recupere une image depuis un fichier SVG
     * 
     * @param string le chemin vers le fichier
     * @return string le SVG
     */
    static function svg($file) {
        if (is_file($file) && is_readable($file)) {
            return file_get_contents($file);
        } else {
            Debug::log('Impossible de charger l\'image vectorielle "' . $file . '" !', Debug::LEVEL_ERROR);
        }
    }
    
}

?>