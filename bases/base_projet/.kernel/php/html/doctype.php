<?php
namespace Kernel\Html;
use Kernel\Configuration;
use Kernel\Debug;



/**
 * Librairie gerant le debut et la fin de la page
 */
class Doctype {

    /**
     * Ouvre une balise HTML et ecris l'entete
     * 
     * @return void
     */
    static function begin() {
        Debug::log('Ouverture du HTML...', Debug::LEVEL_PROGRESS);

        $head = Configuration::get()->website_head;
        $render = Configuration::get()->render;
        $region = Configuration::get()->region;

        Output::add('<!DOCTYPE html>
            <html lang="' . $region->main_lang . '" ' . ($render->wait_dom_loaded ? 'style="opacity: 0;"' : '') . '>
                <head>
                    <meta charset="' . $head->charset . '">
                    <meta name="description" content="' . $head->description . '">
                    <meta name="keywords" content="' . $head->keywords . '">
                    <meta name="author" content="' . $head->author . '">
                    <meta name="viewport" content="' . $head->viewport . '">
                    <meta name="theme-color" content="' . $head->theme_color . '">
                    <meta name="msapplication-navbutton-color" content="' . $head->theme_color . '">
                    <meta name="apple-mobile-web-app-status-bar-style" content="' . $head->theme_color . '">
                    <title>' . $head->title . '</title>
                    <link rel="icon" href="favicon.ico"/>
                </head>');
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
    static function end() {
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
