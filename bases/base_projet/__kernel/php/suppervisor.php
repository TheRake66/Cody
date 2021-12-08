<?php
// Librairie Suppervisor
namespace Kernel;
use Kernel\Router;



class Suppervisor {

    /**
     * Temps UNIX en MS au demarrage du superviseur
     */
    private static $started;


    /**
     * Initialise un superviseur
     */
    static function suppervise() {
        if (isset($_POST['supervisor_refresh'])) {

        }
        if (isset($_POST['supervisor_clear'])) {
            header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }

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
            $ms = round((microtime(true) - self::$started) * 1000);
            $latency = 'SUPPERVISOR_LATENCY_';
            if ($ms > 1000) {
                $latency .= 'BAD';
            } elseif ($ms > 500) {
                $latency .= 'WARN';
            } else {
                $latency .= 'GOOD';
            } 

            
            $array = "";
            foreach ($GLOBALS as $n => $a) {
                if ($n != '_SERVER') { // N'affiche pas les variables serveur
                    if ($n != 'GLOBALS' && is_array($a) && count($a) > 0 || $n == 'GLOBALS' && count($a) > 9) { // Si pas vide (9 == toutes les variable de base dans globals)
                        $array .= '<h2>Données dans $' . $n . '</h2>';
                        $array .= '<div>';
                        foreach ($a as $k => $v) {
                            if ($k != 'GLOBALS' && ($n != 'GLOBALS' || substr($k, 0, 1) != '_')) {
                                $array .= '<span><b>' . $k . '</b>' . (
                                    is_array($v) ? 'array(' . count($v) . ')' :  
                                    (is_object($v) ? 'object' : $v
                                )) . '</span>'; // Affiche la valeur sauf si array ou object
                            }
                        }
                        $array .= '</div>';
                    }
                }
            }

            
            echo '
            <aside class="SUPERVISOR_CODY_PANEL">
                <span>►</span>
                <div>
                    <h1 class="SUPPERVISOR_HTTP_' . $type . '">HTTP ' . $http . '</h1>
                    <h1 class="' . $latency . '">' . $ms . ' ms</h1>
                    <form action=' . \Kernel\Url::current() . ' method="post">
                        <input type="submit" name="supervisor_refresh" value="Actualiser">
                        <input type="submit" name="supervisor_clear" value="Vider le cache">
                    </form>
                    <h2>Informations</h2>
                    <div>
                        <span><b>Session</b>' . $session . '</span>
                        <span><b>Route</b>' . $route . '</span>
                        <span><b>Composant</b>' . $controleur . '</span>
                    </div>' . $array . '</div>
            </aside>
            <style>
                .SUPERVISOR_CODY_PANEL {
                    display: flex;
                    flex-direction: column;
                    position: fixed;
                    z-index: 99999999999999999999;
                    left: 0;
                    top: 0;
                    width: auto;
                    height: 100vh;
                    background-color: #262626;
                    color: white;
                    box-shadow: 0px 0px 10px grey;
                    transition: all 0.3s ease-in-out;
                    transform: translate(-100%, 0);
                }
                .SUPERVISOR_CODY_PANEL:hover {
                    transform: translate(0, 0);
                }
                .SUPERVISOR_CODY_PANEL * {
                    font-family: "Consolas" !important;
                }
                
                
                
                .SUPERVISOR_CODY_PANEL > span {
                    position: absolute;
                    top: 50%; 
                    right: 0;
                    transform: translate(100%, -50%);
                    background-color: #262626;
                    padding: 30px 15px;
                    border-radius: 0 30px 30px 0;
                    font-size: 30px;
                    box-shadow: 0px 0px 10px grey;
                    cursor: pointer;
                }
                
                
                
                .SUPERVISOR_CODY_PANEL > div {
                    overflow-y: scroll;
                    width: 100%;
                    height: 100%;
                }
                .SUPERVISOR_CODY_PANEL > div h1 {
                    padding: 15px 30px;
                    font-weight: 500;
                    font-size: 18px;
                    text-align: center;
                    margin-bottom: 2px;
                }
                .SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_1,
                .SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_LATENCY_GOOD {
                    background-color: #4F805D;
                }
                .SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_2,
                .SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_LATENCY_WARN {
                    background-color: #4F805D;
                }
                .SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_3,
                .SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_LATENCY_BAD {
                    background-color: #A46A1F;
                }
                .SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_4 {
                    background-color: #B0413E;
                }
                .SUPERVISOR_CODY_PANEL > div .SUPPERVISOR_HTTP_5 {
                    background-color: #77064E;
                }
                .SUPERVISOR_CODY_PANEL > div form {
                    margin-top: 20px;
                    display: flex;
                }
                .SUPERVISOR_CODY_PANEL > div form input {
                    padding: 10px;
                    width: 50%;
                    outline: none;
                    border: none;
                    background-color: #777777;
                    color: white;
                    margin: 1px;
                    cursor: pointer;
                }
                .SUPERVISOR_CODY_PANEL > div form input:hover {
                    filter: brightness(110%);
                }
                .SUPERVISOR_CODY_PANEL > div h2 {
                    font-weight: 500;
                    font-size: 16px;
                    padding: 8px;
                    margin-top: 20px;
                    color: lightgray;
                    background-color: #141414;
                }
                .SUPERVISOR_CODY_PANEL > div div {
                    display: flex;
                    flex-direction: column;
                }
                .SUPERVISOR_CODY_PANEL > div div span {
                    min-width: 350px;
                    padding: 8px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                    display: flex;
                }
                .SUPERVISOR_CODY_PANEL > div div span b {
                    min-width: 200px;
                    max-width: 200px;
                    font-weight: 500;
                    color: lightgray;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                }
            </style>
            ';
        }
    }
    
}

?>