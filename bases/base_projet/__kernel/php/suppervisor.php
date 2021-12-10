<?php
// Librairie Suppervisor
namespace Kernel;
use Kernel\Router;



class Suppervisor {

	/**
	 * Les niveaux de criticite
	 */
    const LEVEL_OK = 0;
    const LEVEL_GOOD = 1;
    const LEVEL_WARN = 2;
    const LEVEL_ERROR = 3;
    const LEVEL_PROGRESS = 4;

    /**
     * Temps UNIX en MS au demarrage du superviseur
     */
    private static $started;

    /**
     * Log de la console
     */
    private static $log;


    /**
     * Ajoute une log dans la console
     * 
     * @param string le message a afficher
     * @param int le niveau de criticite
     */
    static function log($message, $level) {
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        self::$log[] = [ '[' . $now->format('H:i:s.v') . '] ' . (
            is_array($message) || is_object($message) ? 
            print_r($message, true) : 
            $message), $level ];
    }


    /**
     * Initialise un superviseur
     */
    static function suppervise() {
        if (isset($_POST['supervisor_refresh'])) {
            self::log('Page actualisée.', self::LEVEL_GOOD);
        }elseif (isset($_POST['supervisor_clear'])) {
            header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            self::log('Cache vidé.', self::LEVEL_GOOD);
        }

		self::log('Lancement du superviseur...', self::LEVEL_PROGRESS);
        self::$started = microtime(true);
    }


    /**
     * Affiche le superviseur
     * 
     * @param string le nom de la route actuelle
     * @param object la classe du controleur
     */
    static function showSuppervisor($route, $controleur) {
        if (Configuration::get()->supperviseur) {
            self::log('Suppervision terminé.', self::LEVEL_GOOD);
            
            $ms = round((microtime(true) - self::$started) * 1000);
            $latency = 'SUPPERVISOR_LATENCY_';
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
                    break;
            }

            
            $array = "";
            foreach ($GLOBALS as $n => $a) {
                if ($n != 'GLOBALS' && is_array($a) && count($a) > 0) {
                    $array .= '<h2>Données dans $' . $n . '</h2>';
                    $array .= '<div>';
                    foreach ($a as $k => $v) {
                        $array .= '<span><b>' . $k . '</b><pre>' . (
                            is_array($v) ? 'array(' . count($v) . ')<br>' . print_r($v, true) :  
                            (is_object($v) ? 'object(' . get_class($v) . ')<br>' . print_r($v, true) : 
                            $v
                        )) . '</pre></span>';
                    }
                    $array .= '</div>';
                }
            }


            $log = '';
            foreach (self::$log as $l) {
                $log .= '
                <pre class="SUPPERVISOR_LEVEL_' . $l[1] . '">' . $l[0] . '</pre>';
            }

            
            echo '
            <aside id="SUPERVISOR_CODY_PANEL">
                <img src="__kernel/logo.svg" alt="Logo">
                <div>
                    <h1 class="SUPPERVISOR_HTTP_' . $type . '">HTTP ' . $http . '</h1>
                    <h1 class="' . $latency . '">' . $ms . ' ms</h1>
                    <form action="' . \Kernel\Url::current() . '" method="post">
                        <input type="submit" name="supervisor_refresh" value="Actualiser">
                        <input type="submit" name="supervisor_clear" value="Vider le cache">
                    </form>
                    <h2>Informations</h2>
                    <div>
                        <span><b>Session</b><pre>' . $session . '</pre></span>
                        <span><b>Route</b><pre>' . $route . '</pre></span>
                        <span><b>Composant</b><pre>' . $controleur . '</pre></span>
                        <span><b>Version de PHP</b><pre>' . phpversion() . '</pre></span>
                    </div>
                    ' . $array . '
                </div>
                <span id="SUPERVISOR_CODY_CONSOLE">
                    ' . $log . '
                </span>
            </aside>
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
                    -webkit-border-radius: 1ex;
                }
                
                #SUPERVISOR_CODY_PANEL *::-webkit-scrollbar-corner {
                    background: #000;
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
                    font-family: "Consolas" !important;
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
                #SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_1 {
                    background-color: #3C7CFC;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_2,
                #SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_LATENCY_GOOD {
                    background-color: #4F805D;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_3,
                #SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_LATENCY_WARN {
                    background-color: #A46A1F;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_4,
                #SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_LATENCY_BAD {
                    background-color: #B0413E;
                }
                #SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_5 {
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
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPPERVISOR_LEVEL_1 {
                    color: #65a577;
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPPERVISOR_LEVEL_2 {
                    color: #d18726;
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPPERVISOR_LEVEL_3 {
                    color: #cc4f4a;
                }
                #SUPERVISOR_CODY_PANEL > #SUPERVISOR_CODY_CONSOLE .SUPPERVISOR_LEVEL_4 {
                    color: #b8127b;
                }
            </style>
            ';
        }
    }
    
}

?>