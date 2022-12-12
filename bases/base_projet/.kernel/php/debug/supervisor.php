<?php
namespace Kernel\Debug;

use Kernel\Security\Configuration;
use Kernel\Io\Path;
use Kernel\Url\Router;
use Kernel\Url\Parser;



/**
 * Librairie du superviseur
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Debug
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Supervisor {

    /**
     * @var int Temps UNIX en MS au démarrage du superviseur.
     */
    private static $started;


    /**
     * @var array Les logs du superviseur.
     */
    private static $log;


    /**
     * Ajoute un log dans la console.
     * 
     * @param string $message Le message à logger.
     * @param int $level Le niveau de criticité du message.
     * @return void
     */
    static function log($message, $level = Log::LEVEL_INFO) {
        if (Configuration::get()->render->debug->supervisor) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $now = $now ? $now->format('H:i:s.v') : '??:??:??.???';
            self::$log[] = [ '[' . $now . '] ' . (
                is_array($message) || is_object($message) ? 
                print_r($message, true) : 
                $message), $level ];
        }
    }


    /**
     * Initialise un superviseur.
     * 
     * @return void
     */
    static function watch() {
        if (Configuration::get()->render->debug->supervisor) {
            if (isset($_POST['supervisor_refresh'])) {
                self::log('Page actualisée.', Log::LEVEL_GOOD);
            }elseif (isset($_POST['supervisor_clear'])) {
                header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
                self::log('Cache vidé.', Log::LEVEL_GOOD);
            }

            self::log('Lancement du superviseur...', Log::LEVEL_PROGRESS);
            self::$started = microtime(true);
        }
    }


    /**
     * Affiche le superviseur.
     * 
     * @return void
     */
    static function show() {
        if (Configuration::get()->render->debug->supervisor) {
            self::log('Supervision terminé.', Log::LEVEL_GOOD);
            
            $ms = round((microtime(true) - self::$started) * 1000);
            $latency = 'SUPERVISOR_LATENCY_';
            if ($ms > 1000) {
                $latency .= 'BAD';
            } elseif ($ms > 500) {
                $latency .= 'WARN';
            } else {
                $latency .= 'GOOD';
            } 

            $http = http_response_code();
            $type = substr($http, 0, 1);
            $session = '';
            switch (session_status()) {
                case 0:
                    $session = 'Désactivée'; 
                    break;
                case 1:
                    $session = 'Aucune'; 
                    break;
                case 2:
                    $session = 'En cours';
                    if (session_name()) {
                        $session .= PHP_EOL . 'Nom : ' . session_name();
                    }
                    if (session_id()) {
                        $session .= PHP_EOL . 'Id : ' . session_id();
                    }
                    break;
            }

            
            $array = "";
            foreach ($GLOBALS as $n => $a) {
                if ($n != 'GLOBALS' && is_array($a) && count($a) > 0) {
                    $array .= '<h2>Données dans $' . $n . '</h2>';
                    $array .= '<div>';
                    foreach ($a as $k => $v) {
                        $array .= '<span><b>' . $k . '</b><pre>' . (
                            is_array($v) ? 'array(' . count($v) . ')<br>' . htmlspecialchars(print_r($v, true), ENT_IGNORE) :  
                            (is_object($v) ? 'object(' . get_class($v) . ')<br>' . htmlspecialchars(print_r($v, true), ENT_IGNORE) : 
                            htmlspecialchars($v, ENT_IGNORE)
                        )) . '</pre></span>';
                    }
                    $array .= '</div>';
                }
            }


            $log = '';
            foreach (self::$log as $l) {
                $log .= '
                <pre class="SUPERVISOR_LEVEL_' . $l[1] . '">' . htmlspecialchars($l[0], ENT_IGNORE) . '</pre>';
            }


            $sequence = Router::sequence();
            $path = '';
            $count = count($sequence);
            if ($count > 1) {
				for ($i=1; $i < $count; $i++) { 
                    $path .= 'Nº "' . $i . ' -> "' . $sequence[$i] . '"<br>';
				}
                $path = substr($path, 0, -4);
            } else {
                $path = 'Aucun';
            }
            
            
            echo('
            <SUPERVISOR_CODY_PANEL id="SUPERVISOR_CODY_PANEL">
                <img src="' . Path::relative('.kernel/cody/logo.svg') . '" alt="Logo">
                <div>
                    <h1 class="SUPERVISOR_HTTP_' . $type . '">HTTP ' . $http . '</h1>
                    <h1 class="' . $latency . '">' . $ms . ' ms</h1>
                    <form action="' . Parser::current() . '" method="post">
                        <input type="submit" name="supervisor_refresh" value="Actualiser">
                        <input type="submit" name="supervisor_clear" value="Vider le cache">
                    </form>
                    <h2>Informations</h2>
                    <div>
                        <span><b>Session</b><pre>' . $session . '</pre></span>
                        <span><b>Route</b><pre>' . Router::current() . '</pre></span>
                        <span><b>Point d\'entrée</b><pre>' . Router::entry() . '</pre></span>
                        <span><b>Enchaînement</b><pre>' . $path . '</pre></span>
                        <span><b>Version de PHP</b><pre>' . phpversion() . '</pre></span>
                    </div>
                    ' . $array . '
                </div>
                <span id="SUPERVISOR_CODY_CONSOLE">
                    ' . $log . '
                </span>
            </SUPERVISOR_CODY_PANEL>
            <script>
                setTimeout(() => {
                    var con = document.getElementById("SUPERVISOR_CODY_CONSOLE");
                    con.scrollTop = con.scrollHeight;
                }, 500);
            </script>
            <style>
            #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar {
                height: 6px !important;
                width: 6px !important;
                background: #141414 !important;
            }
            
            #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-thumb {
                background: #444 !important;
                border-radius: 1ex !important;
            }
            
            #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-thumb:hover {
                background: #555 !important;
            }
            
            #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-thumb:active {
                background: #666 !important;
            }
            
            #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-corner {
                background: #141414 !important;
            }

            #SUPERVISOR_CODY_PANEL {
                display: flex !important;
                flex-direction: column !important;
                position: fixed !important;
                z-index: 99999999999999999999 !important;
                left: 0 !important;
                top: 0 !important;
                min-width: 600px !important;
                max-width: 600px !important;
                height: 100vh !important;
                background-color: #262626 !important;
                color: white !important;
                transition: transform 0.3s ease-in-out !important;
                transform: translate(-100%, 0) !important;
                border-right: solid 2px #777777 !important;
            }
            #SUPERVISOR_CODY_PANEL:hover {
                transform: translate(0, 0) !important;
            }
            #SUPERVISOR_CODY_PANEL * {
                font-family: "cody_consolas" !important;
                scroll-behavior: auto !important;
                box-sizing: unset !important;
                margin: 0 !important;
                padding: 0 !important;
                color: white !important;
                border-radius: 0px !important;
            }
        


            @font-face {
                font-family: "cody_consolas";
                src: url("' . Path::relative('.kernel/cody/consolas.ttf') . '") format("truetype");
            }

            
            #SUPERVISOR_CODY_PANEL > img {
                position: absolute !important;
                height: 30px !important;
                width: 30px !important;
                top: 50% !important; 
                right: 0 !important;
                transform: translate(100%, -50%) !important;
                background-color: #262626 !important;
                padding: 30px 15px 30px 12px !important;
                border-radius: 0 30px 30px 0 !important;
                font-size: 30px !important;
                border: solid 2px #777777 !important;
                border-left: none !important;

            }
            
            
            #SUPERVISOR_CODY_PANEL > div {
                overflow-y: scroll !important;
                width: 100% !important;
                height: 100% !important;
                padding-bottom: 20px !important;
            }
            #SUPERVISOR_CODY_PANEL > div h1 {
                padding: 15px 30px !important;
                font-weight: 500 !important;
                font-size: 18px !important;
                text-align: center !important;
                margin-bottom: 2px !important;
            }
            #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_1 {
                background-color: #3C7CFC !important;
            }
            #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_2,
            #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_LATENCY_GOOD {
                background-color: #4F805D !important;
            }
            #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_3,
            #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_LATENCY_WARN {
                background-color: #A46A1F !important;
            }
            #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_4,
            #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_LATENCY_BAD {
                background-color: #B0413E !important;
            }
            #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_5 {
                background-color: #77064E !important;
            }
            #SUPERVISOR_CODY_PANEL > div form {
                margin-top: 20px !important;
                display: flex !important;
            }
            #SUPERVISOR_CODY_PANEL > div form input {
                padding: 10px !important;
                width: 50% !important;
                height: fit-content !important;
                border-radius: 0px !important;
                outline: none !important;
                border: none !important;
                background-color: #777777 !important;
                color: white !important;
                margin: 1px !important;
                cursor: pointer !important;
            }
            #SUPERVISOR_CODY_PANEL > div form input:hover {
                filter: brightness(110%) !important;
            }
            #SUPERVISOR_CODY_PANEL > div h2 {
                font-weight: 500 !important;
                font-size: 16px !important;
                padding: 8px !important;
                margin-top: 20px !important;
                color: lightgray !important;
                background-color: #141414 !important;
            }
            #SUPERVISOR_CODY_PANEL > div div {
                display: flex !important;
                flex-direction: column !important;
            }
            #SUPERVISOR_CODY_PANEL > div div span {
                display: flex !important;
                align-items: baseline !important;
                overflow: auto !important;
                padding: 8px !important;
            }
            #SUPERVISOR_CODY_PANEL > div div span pre {
            }
            #SUPERVISOR_CODY_PANEL > div div span b {
                min-width: 200px !important;
                max-width: 200px !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                margin-right: 20px !important;
                font-weight: 500 !important;
                color: lightgray !important;

            }


            #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE {
                background-color: #0C0C0C !important;
                width: calc(100% - 10px) !important;
                height: 250px !important;
                overflow: scroll !important;
                padding: 5px !important;
                border-top: solid 2px #777777 !important;
            }
            #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE pre {
            }
            #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_0 {
                color: #CCCCCC !important;
            }
            #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_1 {
                color: #65a577 !important;
            }
            #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_2 {
                color: #d18726 !important;
            }
            #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_3 {
                color: #cc4f4a !important;
            }
            #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_4 {
                color: #b8127b !important;
            }
            </style>
            ');
        }
    }
    
}

?>