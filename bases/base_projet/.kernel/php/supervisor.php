<?php
namespace Kernel;
use Kernel\Router;
use Kernel\Url;
use Kernel\Debug;



/**
 * Librairie du superviseur
 */
class Supervisor {

    /**
     * @var int Temps UNIX en MS au demarrage du superviseur
     */
    private static $started;

    /**
     * @var array Log de la console
     */
    private static $log;


    /**
     * Ajoute une log dans la console
     * 
     * @param string le message a afficher
     * @param int le niveau de criticite
     * @return void
     */
    static function log($message, $level = Debug::LEVEL_INFO) {
        if (Configuration::get()->render->show_supervisor) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $now = $now ? $now->format('H:i:s.v') : '??:??:??.???';
            self::$log[] = [ '[' . $now . '] ' . (
                is_array($message) || is_object($message) ? 
                print_r($message, true) : 
                $message), $level ];
        }
    }


    /**
     * Initialise un superviseur
     * 
     * @return void
     */
    static function supervise() {
        if (Configuration::get()->render->show_supervisor) {
            if (isset($_POST['supervisor_refresh'])) {
                self::log('Page actualisée.', Debug::LEVEL_GOOD);
            }elseif (isset($_POST['supervisor_clear'])) {
                header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
                self::log('Cache vidé.', Debug::LEVEL_GOOD);
            }

            self::log('Lancement du superviseur...', Debug::LEVEL_PROGRESS);
            self::$started = microtime(true);
        }
    }


    /**
     * Affiche le superviseur
     * 
     * @return void
     */
    static function show() {
        if (Configuration::get()->render->show_supervisor) {
            self::log('Supervision terminé.', Debug::LEVEL_GOOD);
            
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
                        $session .= PHP_EOL . 'ID : ' . session_id();
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

            
            echo '
            <SUPERVISOR_CODY_PANEL id="SUPERVISOR_CODY_PANEL">
                <img src="' . Path::relative('.kernel/logo.svg') . '" alt="Logo">
                <div>
                    <h1 class="SUPERVISOR_HTTP_' . $type . '">HTTP ' . $http . '</h1>
                    <h1 class="' . $latency . '">' . $ms . ' ms</h1>
                    <form action="' . Url::current() . '" method="post">
                        <input type="submit" name="supervisor_refresh" value="Actualiser">
                        <input type="submit" name="supervisor_clear" value="Vider le cache">
                    </form>
                    <h2>Informations</h2>
                    <div>
                        <span><b>Session</b><pre>' . $session . '</pre></span>
                        <span><b>Route</b><pre>' . Router::get() . '</pre></span>
                        <span><b>Composant</b><pre>' . Router::getController() . '</pre></span>
                        <span><b>Version de PHP</b><pre>' . phpversion() . '</pre></span>
                    </div>
                    ' . $array . '
                </div>
                <span id="SUPERVISOR_CODY_CONSOLE">
                    ' . $log . '
                </span>
            </SUPERVISOR_CODY_PANEL>
            <script>
                async function SUPERVISOR_CODY_CONSOLE_SCROLLTOEND() {
                    await new Promise(r => setTimeout(r, 500));
                    var con = document.getElementById("SUPERVISOR_CODY_CONSOLE");
                    con.scrollTop = con.scrollHeight;
                }
                SUPERVISOR_CODY_CONSOLE_SCROLLTOEND();
            </script>
            <style>
                #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar {
                    height: 6px;
                    width: 6px;
                    background: #141414;
                }
                
                #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-thumb {
                    background: #444;
                    border-radius: 1ex;
                }
                
                #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-thumb:hover {
                    background: #555;
                }
                
                #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-thumb:active {
                    background: #666;
                }
                
                #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-corner {
                    background: #141414;
                }

                #SUPERVISOR_CODY_PANEL {
                    display: flex;
                    flex-direction: column;
                    position: fixed;
                    z-index: 99999999999999999999;
                    left: 0;
                    top: 0;
                    min-width: 600px;
                    max-width: 600px;
                    height: 100vh;
                    background-color: #262626;
                    color: white;
                    transition: transform 0.3s ease-in-out;
                    transform: translate(-100%, 0);
                    border-right: solid 2px #777777;
                }
                #SUPERVISOR_CODY_PANEL:hover {
                    transform: translate(0, 0);
                }
                #SUPERVISOR_CODY_PANEL * {
                    font-family: "cody_consolas" !important;
                    scroll-behavior: auto;
					box-sizing: unset;
                    margin: 0;
                    padding: 0;
                }
            


                @font-face {
                    font-family: "cody_consolas";
                    src: url("' . Path::relative('.kernel/consolas.ttf') . '") format("truetype");
                }

                
                #SUPERVISOR_CODY_PANEL > img {
                    position: absolute;
                    height: 30px;
                    width: 30px;
                    top: 50%; 
                    right: 0;
                    transform: translate(100%, -50%);
                    background-color: #262626;
                    padding: 30px 15px 30px 12px;
                    border-radius: 0 30px 30px 0;
                    font-size: 30px;
                    border: solid 2px #777777;
                    border-left: none;

                }
                
                
                #SUPERVISOR_CODY_PANEL > div {
                    overflow-y: scroll;
                    width: 100%;
                    height: 100%;
                    padding-bottom: 20px;
                }
                #SUPERVISOR_CODY_PANEL > div h1 {
                    padding: 15px 30px;
                    font-weight: 500;
                    font-size: 18px;
                    text-align: center;
                    margin-bottom: 2px;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_1 {
                    background-color: #3C7CFC;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_2,
                #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_LATENCY_GOOD {
                    background-color: #4F805D;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_3,
                #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_LATENCY_WARN {
                    background-color: #A46A1F;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_4,
                #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_LATENCY_BAD {
                    background-color: #B0413E;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPERVISOR_HTTP_5 {
                    background-color: #77064E;
                }
                #SUPERVISOR_CODY_PANEL > div form {
                    margin-top: 20px;
                    display: flex;
                }
                #SUPERVISOR_CODY_PANEL > div form input {
                    padding: 10px;
                    width: 50%;
                    outline: none;
                    border: none;
                    background-color: #777777;
                    color: white;
                    margin: 1px;
                    cursor: pointer;
                }
                #SUPERVISOR_CODY_PANEL > div form input:hover {
                    filter: brightness(110%);
                }
                #SUPERVISOR_CODY_PANEL > div h2 {
                    font-weight: 500;
                    font-size: 16px;
                    padding: 8px;
                    margin-top: 20px;
                    color: lightgray;
                    background-color: #141414;
                }
                #SUPERVISOR_CODY_PANEL > div div {
                    display: flex;
                    flex-direction: column;
                }
                #SUPERVISOR_CODY_PANEL > div div span {
                    display: flex;
                    align-items: baseline;
                    overflow: auto;
                    padding: 8px;
                }
                #SUPERVISOR_CODY_PANEL > div div span pre {
                }
                #SUPERVISOR_CODY_PANEL > div div span b {
                    min-width: 200px;
                    max-width: 200px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    margin-right: 20px;
                    font-weight: 500;
                    color: lightgray;

                }


                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE {
                    background-color: #0C0C0C;
                    width: calc(100% - 10px);
                    height: 250px;
                    overflow: scroll;
                    padding: 5px;
                    border-top: solid 2px #777777;
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE pre {
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_0 {
                    color: #CCCCCC;
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_1 {
                    color: #65a577;
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_2 {
                    color: #d18726;
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_3 {
                    color: #cc4f4a;
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPERVISOR_LEVEL_4 {
                    color: #b8127b;
                }
            </style>
            ';
        }
    }
    
}

?>