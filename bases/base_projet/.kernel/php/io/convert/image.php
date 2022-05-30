<?php
namespace Kernel\IO\Convert;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\IO\Stream;

/**
 * Librairie traitant les images
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Image {

    /**
     * Compresse une image avec la taille souhaiter
     * 
     * @param string les donnees de l'image
     * @param int la largeur finale
     * @param int la hauteur finale
     * @return string l'image compressee
     */
    static function resize($data, $width = 128, $height = 128, $stretch = false) {
        if (extension_loaded('gd') || extension_loaded('gd2')) {
            $source = imagecreatefromstring($data);
            $info = getimagesizefromstring($data);
            $width_or = imagesx($source);
            $height_or = imagesy($source);

            if (!$stretch) {
                $ratio = min($width / $width_or, $height / $height_or);
                $width = $width_or * $ratio;
                $height = $height_or * $ratio;
            }

            $dest = imagecreatetruecolor($width, $height);
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            imagecopyresampled($dest, $source, 0, 0, 0, 0, $width, $height, $width_or, $height_or);
            
            Stream::start();
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
            $compress =  Stream::get();
            Stream::destroy();
    
            return $compress;
        } else {
            Error::trigger('L\'extension GD/GD2 n\'est pas disponible !');
        }
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
            Log::add('Impossible de charger l\'image vectorielle "' . $file . '" !', Log::LEVEL_ERROR);
        }
    }


    /**
     * Convertit une entree binaire en image base64
     *
     * @param object le binaire de l'image
     * @param string le format de l'image
     * @return string l'image en base64
     */
    static function B64($bin, $format = 'png') {
        return 'data:image/'  . $format . ';base64,' . base64_encode($bin);
    }
    
}

?>