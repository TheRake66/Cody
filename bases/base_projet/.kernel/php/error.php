<?php
namespace Kernel;
use Exception;



/**
 * Librairie gerant les messages d'erreur
 */
class Error {

    /**
     * Empeche l'appel des evennements dans l'affichage de l'erreur
     * Evite les appels en boucle
     * 
     * @var bool
     */
    private static $showing = false;


    /**
     * Initialise les evennements d'appel
     * 
     * @return void
     */
    static function handler() {
        if (!self::$showing && Configuration::get()->render->catch_error) {
            set_error_handler(function($severity, $message, $filename, $lineno) {
                self::showError($severity, $message, $filename, $lineno);
            });
            register_shutdown_function(function() {
                self::showFatal();
            });
        }
    }


    /**
     * Supprime les evennements d'appel
     * 
     * @return void
     */
    static function remove() { 
        if (Configuration::get()->render->catch_error) {
            set_error_handler(function() { });
            register_shutdown_function(function() { });
        }
    }


    /**
     * Recupere et affiche un message d'erreur fatal
     * 
     * @return void
     */
    private static function showFatal() {
        $error = error_get_last();
        if($error !== null) {
            $severity = $error["type"];
            $filename = $error["file"];
            $lineno = $error["line"];
            $message = $error["message"];
            self::showError($severity, $message, $filename, $lineno);
        }
    }


    /**
     * Affiche un message d'erreur
     * 
     * @param int code erreur
     * @param string le message
     * @param string le fichier concerner
     * @param int le numero de la ligne
     * @return void
     */
    private static function showError($severity, $message, $filename, $lineno) {
        self::remove();
        self::$showing = true;
        $message .= PHP_EOL . (new Exception())->getTraceAsString();
        Debug::log($message, Debug::LEVEL_ERROR);
        Stream::destroy();
        http_response_code(500);
        if (Configuration::get()->render->show_error_message) {
            Stream::start();
            $search = urlencode($message);
            echo '
            <div class="ERROR_CODY_BLOCK">
                <div class="ERROR_CODY_HEAD">
                    <div>      
                        <img src=".kernel/logo_full.png" alt="Cody">
                        <span>Une erreur est survenue !</span>
                    </div>
                </div>
                <div class="ERROR_CODY_CONT">
                    <span><b>Code erreur :</b><input type="text" value="' . $severity . '" readonly></span>
                    <span><b>Message :</b><textarea readonly>' . $message . '</textarea></span>
                    <span><b>Fichier concerné :</b><input type="text" value="' . $filename . '" readonly></span>
                    <span><b>Ligne concernée :</b><input type="text" value="' . $lineno . '" readonly></span>
                    <div>
                        <a target="_blank" href="https://www.google.com/search?q=' . $search . '">Rechercher sur google</a>
                        <a target="_blank" href="https://stackoverflow.com/search?q=' . $search . '">Rechercher sur stackoverflow</a>
                    </div>
                </div>
            </div>
            <style>

                .ERROR_CODY_BLOCK *::-webkit-scrollbar {
                    height: 6px;
                    width: 6px;
                    background: #3B78FF;
                }
                
                .ERROR_CODY_BLOCK *::-webkit-scrollbar-thumb {
                    background: #779FFF;
                    border-radius: 1ex;
                }
                
                .ERROR_CODY_BLOCK *::-webkit-scrollbar-corner {
                    background: #3B78FF;
                }

                .ERROR_CODY_BLOCK *::-webkit-resizer {
                    background: #3B78FF;
                }


                .ERROR_CODY_BLOCK {
                    margin: auto;
                    font-family: "cody_consolas" !important;
                    font-size: 14px;
                    display: flex;
                    flex-direction: column;
                    box-shadow: 0 0 10px grey;
                    border: solid 2px #3B78FF;
                    border-radius: 5px;
                    overflow: hidden;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background-color: white;
                }
                .ERROR_CODY_BLOCK * {
                    font-family: "cody_consolas" !important;
                    margin: 0;
                    padding: 0;
                }

                @font-face {
                    font-family: "cody_consolas";
                    src: url(".kernel/consolas.ttf") format("truetype");
                }


                .ERROR_CODY_HEAD {
                    background-color: #3B78FF;
                    background: linear-gradient(
                        90deg, #3b78ff 30%, #779fff 70%);
                            box-shadow: 0 5px 5px grey;
                    height: 210px;
                }
                .ERROR_CODY_HEAD div {
                    background-image: url(./.kernel/triangles.svg);
                    background-size: contain;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    height: 100%;
                }
                .ERROR_CODY_HEAD div img {
                    margin: 25px;
                    margin-bottom: 10px;
                    width: 250px;
                    filter: brightness(0) invert(1);
                }
                .ERROR_CODY_HEAD div span {
                    color: white;
                    font-size: 24px;
                    margin: 25px;
                    margin-top: 10px;
                }


                .ERROR_CODY_CONT {
                    padding: 30px;
                    display: flex;
                    flex-direction: column;
                }
                .ERROR_CODY_CONT span {
                    display: flex;
                    align-items: baseline;
                    width: 800px;
                    margin-bottom: 20px;
                }
                .ERROR_CODY_CONT input, .ERROR_CODY_CONT textarea {
                    max-width: 550px;
                    min-width: 550px;
                    box-shadow: 0 0 10px grey;
                    border: solid 2px #3B78FF;
                    border-radius: 5px;
                    outline: none;
                    padding: 15px;
                    background-color: white;
                    margin-left: auto;
                }
                .ERROR_CODY_CONT textarea {
                    height: 100px;
                    white-space: pre;
                    overflow: scroll;
                }


                .ERROR_CODY_CONT div {
                    margin: auto;
                    margin-bottom: 20px;
                    margin-top: 30px;
                }
                .ERROR_CODY_CONT div a {
                    margin: 10px;
                    text-decoration: none;
                    outline: none;
                    border: none;
                    border-radius: 100px;
                    padding: 15px 30px;
                    background-color: #3B78FF;
                    box-shadow: 0 2px 4px grey;
                    color: white;
                    cursor: pointer;
                    transition: all 0.2s ease-in-out;
                    font-size: 16px;
                }
                .ERROR_CODY_CONT div a:hover {
                    filter: brightness(120%);
                }
                
            </style>';
            Stream::close();
        }
        die;
    }
    
}

?>