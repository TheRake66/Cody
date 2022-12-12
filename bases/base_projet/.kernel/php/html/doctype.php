<?php
namespace Kernel\Html;

use Kernel\Security\Configuration;
use Kernel\Debug\Log;
use Kernel\Io\Path;
use Kernel\Url\Parser;

/**
 * Librairie gérant le début et la fin de la page.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Html
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Doctype {

    /**
     * Ouvre une balise HTML et écris l'entête.
     * 
     * @return void
     */
    static function open() {
        Log::add('Ouverture du HTML...', Log::LEVEL_PROGRESS);

        $conf_website = Configuration::get()->website;
        $conf_render = Configuration::get()->render;
        $conf_region = Configuration::get()->region;
		$conf_og = $conf_website->opengraph;
		$conf_og_image = $conf_og->image;
		$conf_http = $conf_website->http;
		$conf_refresh = $conf_http->refresh;

        $meta_charset = Builder::create('meta', [ 'charset' => $conf_website->charset ]);
        $meta_description = Builder::create('meta', [ 'name' => 'description', 'content' => $conf_website->description ]);
        $meta_keywords = Builder::create('meta', [ 'name' => 'keywords', 'content' => $conf_website->keywords ]);
        $meta_viewport = Builder::create('meta', [ 'name' => 'viewport', 'content' => $conf_website->viewport ]);
        $meta_robots = Builder::create('meta', [ 'name' => 'robots', 'content' => $conf_website->robots ]);
        $meta_author = Builder::create('meta', [ 'name' => 'author', 'content' => $conf_website->author ]);
        $meta_theme_color = Builder::create('meta', [ 'name' => 'theme-color', 'content' => $conf_website->theme_color ]);
        $meta_theme_color_apple = Builder::create('meta', [ 'name' => 'apple-mobile-web-app-status-bar-style', 'content' => $conf_website->theme_color ]);
        $meta_theme_color_ms = Builder::create('meta', [ 'name' => 'msapplication-navbutton-color', 'content' => $conf_website->theme_color ]);

        $meta_og_title = Builder::create('meta', [ 'property' => 'og:title', 'content' => $conf_og->title ]);
		$meta_og_description = Builder::create('meta', [ 'property' => 'og:description', 'content' => $conf_og->description ]);
		$meta_og_url = Builder::create('meta', [ 'property' => 'og:url', 'content' => $conf_og->url ]);
		$meta_og_type = Builder::create('meta', [ 'property' => 'og:type', 'content' => $conf_og->type ]);
		$meta_og_locale = Builder::create('meta', [ 'property' => 'og:locale', 'content' => $conf_og->locale ]);
		$meta_og_site_name = Builder::create('meta', [ 'property' => 'og:site_name', 'content' => $conf_og->site_name ]);
		$meta_og_image_secure_url = Builder::create('meta', [ 'property' => 'og:image:secure_url', 'content' => $conf_og_image->secure_url ]);
		$meta_og_image_url = Builder::create('meta', [ 'property' => 'og:image:url', 'content' => $conf_og_image->url ]);
		$meta_og_image_type = Builder::create('meta', [ 'property' => 'og:image:type', 'content' => $conf_og_image->type ]);
		$meta_og_image_width = Builder::create('meta', [ 'property' => 'og:image:width', 'content' => $conf_og_image->width ]);
		$meta_og_image_height = Builder::create('meta', [ 'property' => 'og:image:height', 'content' => $conf_og_image->height ]);
		$meta_og_image_alt = Builder::create('meta', [ 'property' => 'og:image:alt', 'content' => $conf_og_image->alt ]);

        $meta_equiv_cache = Builder::create('meta', [ 'http-equiv' => 'Cache', 'content' => $conf_http->cache ]);
        $meta_equiv_cache_control = Builder::create('meta', [ 'http-equiv' => 'Cache-control', 'content' => $conf_http->cache_control ]);
        $meta_equiv_pragma = Builder::create('meta', [ 'http-equiv' => 'Pragma', 'content' => $conf_http->pragma ]);
        $meta_equiv_expires = Builder::create('meta', [ 'http-equiv' => 'Expires', 'content' => $conf_http->expires ]);

		$meta_equiv_refresh = $conf_refresh->enabled ?
			Builder::create('meta', [ 'http-equiv' => 'refresh', 'content' => $conf_refresh->delay . '; url=' . $conf_refresh->url ]) :
			'' ;
        
        $title = Builder::create('title', null, $conf_website->title);
        $link_favicon = Builder::create('link', [ 'rel' => 'icon', 'href' => $conf_website->favicon ]);
        
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

			$meta_og_title,
			$meta_og_description,
			$meta_og_url,
			$meta_og_type,
			$meta_og_locale,
			$meta_og_site_name,
			$meta_og_image_secure_url,
			$meta_og_image_url,
			$meta_og_image_type,
			$meta_og_image_width,
			$meta_og_image_height,
			$meta_og_image_alt,

			$meta_equiv_cache,
			$meta_equiv_cache_control,
			$meta_equiv_pragma,
			$meta_equiv_expires,
			$meta_equiv_refresh,

			$title,
			$link_favicon
        ]);
        $html = Builder::create('html', [
            'lang' => $conf_region->main_lang,
            'style' => 'opacity: ' . ($conf_render->wait_dom_loaded ? 0 : 1)
        ], $head);
        
        Output::add('<!DOCTYPE html>' . $html);
        Log::add('Définition de l\'entête.');
    
        Output::add(Less::import('.kernel/global.less'));
        Log::add('Style global importé.');

        Output::add(Javascript::import('.kernel/global_brefore.js'));
        Log::add('Script d\'initialisation importé.');
        
        Log::add('HTML ouvert.', Log::LEVEL_GOOD);
    }


    /**
     * Ferme la balise HTML.
     * 
     * @return void
     */
    static function close() {
        Log::add('Fermeture du HTML...', Log::LEVEL_PROGRESS);

        Output::add(Javascript::import('.kernel/global_after.js'));
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
