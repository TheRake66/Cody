<?php

namespace Librairie;



class Debug {

    /**
     * Affiche un message dans la console en JavaScript
     * 
     * @param string Message a afficher
     */
	public static function consoleMessage($unMessage) {
		echo '<script>';
		echo 'console.log('. json_encode('[' .date('d/m/Y') . ' ' . date('H:i:s') . '] ==>' . $unMessage . '<==') .')';
		echo '</script>';
	}
	

    /**
     * Ajoute un message dans un fichier log
     * 
     * @param string Message a ajouter
     */
	public static function logMessage($leMessage) {
        $continu = true;
        if (!is_dir('logs')) {
            $continu = mkdir('logs');
        }
        if ($continu) {
            file_put_contents(
                'logs/' . date('D M d') . '.log',
                '[' . date('D M d, Y G:i') . '] ' . $leMessage . PHP_EOL,
                FILE_APPEND
            );
        }
	}
	

    /**
     * Affiche un message dans une message box
     * 
     * @param string Message a afficher
     */
	public static function boiteMessage($leMessage) {
        echo "<script>alert('{$leMessage}');</script>";
	}

}

?>