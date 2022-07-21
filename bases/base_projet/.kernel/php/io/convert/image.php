<?php
namespace Kernel\Io\Convert;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\Io\File;
use Kernel\IO\Path;
use Kernel\Io\Stream;



/**
 * Librairie traitant les images.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Image {

    /**
     * Compresse une image avec la taille spécifiée.
     * 
     * @param string $data La donnée à compresser.
     * @param int $width La largeur de l'image.
     * @param int $height La hauteur de l'image.
     * @param bool $stretched Si l'image doit être étirée.
     * @return string La donnée compressée.
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
     * Charge une image SVG depuis les assets.
     * 
     * @example Kernel\Io\Convert\Image::svg('logo/test'); =>
     *      Chargera : 'C:/wamp64/www/project/assets/images/svg/logo/test.svg'
     * @param string $file Le fichier SVG.
     * @return string La balise SVG.
     */
    static function svg($file) {
        return File::load('/assets/img/' . $file . '.svg');
    }


    /**
     * Convertit une entrée binaire en image base64.
     *
     * @param string $bin La donnée binaire.
     * @param string $format Le format de l'image.
     * @return string La chaîne base64.
     */
    static function b64($bin, $format = 'png') {
        return 'data:image/'  . $format . ';base64,' . base64_encode($bin);
    }
    
}

?>