<?php

namespace Kernel\Debug;

use Kernel\Communication\Network;
use Kernel\Communication\Http;
use Kernel\IO\Stream;
use Kernel\Security\Configuration;
use Kernel\Url\Location;



/**
 * Librairie gérant la maintenance du site.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @package Kernel\Debug
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Maintenance {

    /**
     * Redirige vers une page de maintenance si le site est en maintenance.
     * 
     * @return void
     */
    static function redirect() {
        $conf = Configuration::get()->render->debug->maintenance;
        if ($conf->enabled) {
            $ip = Network::client();
            if (($conf->alow_localhost && $ip !== "::1" && !in_array($ip, $conf->authorized_ips, true)) || 
                (!$conf->alow_localhost && !in_array($ip, $conf->authorized_ips, true))) {
                $conf2 = $conf->redirect;
                if ($conf2->enabled) {
                    Location::change($conf2->url);
                } else {
                    Stream::destroy();
                    http_response_code(Http::HTTP_SERVICE_UNAVAILABLE);
                    exit();
                }
            }
        }
    }
    
}

?>