<?php
namespace Kernel\HTML;

use Kernel\Security\Configuration;
use Kernel\Debug\Log;
use Kernel\IO\Path;



/**
 * Librairie gerant le debut et la fin de la page
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\HTML
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Doctype {

    /**
     * Ouvre une balise HTML et ecris l'entete
     * 
     * @return void
     */
    static function open() {
        Log::add('Ouverture du HTML...', Log::LEVEL_PROGRESS);

        $conf_head = Configuration::get()->website_head;
        $conf_render = Configuration::get()->render;
        $conf_region = Configuration::get()->region;

        $meta_charset = Builder::createElement('meta', [ 'charset' => $conf_head->charset ]);
        $meta_description = Builder::createElement('meta', [ 'name' => 'description', 'content' => $conf_head->description ]);
        $meta_keywords = Builder::createElement('meta', [ 'name' => 'keywords', 'content' => $conf_head->keywords ]);
        $meta_viewport = Builder::createElement('meta', [ 'name' => 'viewport', 'content' => $conf_head->viewport ]);
        $meta_robots = Builder::createElement('meta', [ 'name' => 'robots', 'content' => $conf_head->robots ]);
        $meta_author = Builder::createElement('meta', [ 'name' => 'author', 'content' => $conf_head->author ]);
        $meta_theme_color = Builder::createElement('meta', [ 'name' => 'theme-color', 'content' => $conf_head->theme_color ]);
        $meta_theme_color_apple = Builder::createElement('meta', [ 'name' => 'apple-mobile-web-app-status-bar-style', 'content' => $conf_head->theme_color ]);
        $meta_theme_color_ms = Builder::createElement('meta', [ 'name' => 'msapplication-navbutton-color', 'content' => $conf_head->theme_color ]);
        $title = Builder::createElement('title', null, $conf_head->title);
        $link_favicon = Builder::createElement('link', [ 'rel' => 'icon', 'href' => Path::relative('favicon.ico') ]);
        $head = Builder::createElement('head', null, [
            $meta_charset,
            $meta_description,
            $meta_keywords,
            $meta_viewport,
            $meta_robots,
            $meta_author,
            $meta_theme_color,
            $meta_theme_color_apple,
            $meta_theme_color_ms,
            $title,
            $link_favicon
        ]);
        $html = Builder::createElement('html', [
            'lang' => $conf_region->main_lang,
            'style' => 'opacity: ' . ($conf_render->wait_dom_loaded ? 0 : 1)
        ], $head);
        $doctype = Builder::createElement('!DOCTYPE html', null, $html);

        Output::add($doctype);
        Log::add('Définition de l\'entête.');
    
        Output::add(Less::import('debug/app/global.less'));
        Log::add('Style global importé.');

        Output::add(Javascript::import('debug/app/global_brefore.js'));
        Log::add('Script d\'initialisation importé.');
        
        Log::add('HTML ouvert.', Log::LEVEL_GOOD);
    }


    /**
     * Ferme la balise HTML
     * 
     * @return void
     */
    static function close() {
        Log::add('Fermeture du HTML...', Log::LEVEL_PROGRESS);

        Output::add(Javascript::import('debug/app/global_after.js'));
        Log::add('Script d\'extinction importé.');

        $render = Configuration::get()->render;
        if ($render->wait_dom_loaded) {
            Output::add(Javascript::run('
                async function loaded() {
                    await new Promise(r => setTimeout(r, ' . $render->delay_after_load . '));
                    document.getElementsByTagName(\'html\')[0].style.opacity = 1;
                }
                window.addEventListener("DOMContentLoaded", (event) => {
                    loaded();
                });'));
            Log::add('Définition de la fonction de rendu.');
        }

        Output::add('</html>');
        Log::add('HTML fermé.', Log::LEVEL_GOOD);
    }
    
}
