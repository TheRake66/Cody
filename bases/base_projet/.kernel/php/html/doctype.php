<?php
namespace Kernel\Html;
use Kernel\Configuration;
use Kernel\Debug;
use Kernel\Path;

/**
 * Librairie gerant le debut et la fin de la page
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Doctype {

    /**
     * Ouvre une balise HTML et ecris l'entete
     * 
     * @return void
     */
    static function open() {
        Debug::log('Ouverture du HTML...', Debug::LEVEL_PROGRESS);

        $conf_head = Configuration::get()->website_head;
        $conf_render = Configuration::get()->render;
        $conf_region = Configuration::get()->region;

        $meta_charset = Builder::create('meta', [ 'charset' => $conf_head->charset ]);
        $meta_description = Builder::create('meta', [ 'name' => 'description', 'content' => $conf_head->description ]);
        $meta_keywords = Builder::create('meta', [ 'name' => 'keywords', 'content' => $conf_head->keywords ]);
        $meta_viewport = Builder::create('meta', [ 'name' => 'viewport', 'content' => $conf_head->viewport ]);
        $meta_robots = Builder::create('meta', [ 'name' => 'robots', 'content' => $conf_head->robots ]);
        $meta_author = Builder::create('meta', [ 'name' => 'author', 'content' => $conf_head->author ]);
        $meta_theme_color = Builder::create('meta', [ 'name' => 'theme-color', 'content' => $conf_head->theme_color ]);
        $meta_theme_color_apple = Builder::create('meta', [ 'name' => 'apple-mobile-web-app-status-bar-style', 'content' => $conf_head->theme_color ]);
        $meta_theme_color_ms = Builder::create('meta', [ 'name' => 'msapplication-navbutton-color', 'content' => $conf_head->theme_color ]);
        $title = Builder::create('title', null, $conf_head->title);
        $link_favicon = Builder::create('link', [ 'rel' => 'icon', 'href' => Path::relative('favicon.ico') ]);
        $head = Builder::create('head', null, [
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
        $html = Builder::create('html', [
            'lang' => $conf_region->main_lang,
            'style' => 'opacity: ' . ($conf_render->wait_dom_loaded ? 0 : 1)
        ], $head);
        $doctype = Builder::create('!DOCTYPE html', null, $html);

        Output::add($doctype);
        Debug::log('Définition de l\'entête.');
    
        Output::add(Import::importStyle('debug/app/global.less'));
        Debug::log('Style global importé.');

        Output::add(Import::importScript('debug/app/global_brefore.js'));
        Debug::log('Script d\'initialisation importé.');
        
        Debug::log('HTML ouvert.', Debug::LEVEL_GOOD);
    }


    /**
     * Ferme la balise HTML
     * 
     * @return void
     */
    static function close() {
        Debug::log('Fermeture du HTML...', Debug::LEVEL_PROGRESS);

        Output::add(Import::importScript('debug/app/global_after.js'));
        Debug::log('Script d\'extinction importé.');

        $render = Configuration::get()->render;
        if ($render->wait_dom_loaded) {
            Output::add(Import::runScript('
                async function loaded() {
                    await new Promise(r => setTimeout(r, ' . $render->delay_after_load . '));
                    document.getElementsByTagName(\'html\')[0].style.opacity = 1;
                }
                window.addEventListener("DOMContentLoaded", (event) => {
                    loaded();
                });'));
            Debug::log('Définition de la fonction de rendu.');
        }

        Output::add('</html>');
        Debug::log('HTML fermé.', Debug::LEVEL_GOOD);
    }
    
}
