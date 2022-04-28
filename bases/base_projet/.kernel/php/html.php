<?php
namespace Kernel;
use Kernel\Url;
use Kernel\Configuration;



/**
 * Librairie gerant les balises HTML
 */
class Html {

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

        self::add('<!DOCTYPE html>
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
    
        self::add(self::importStyle('debug/app/global.less'));
        Debug::log('Style global importé.');

        self::add(self::importScript('debug/app/global_brefore.js'));
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

        self::add(self::importScript('debug/app/global_after.js'));
        Debug::log('Script d\'extinction importé.');

        $render = Configuration::get()->render;
        if ($render->wait_dom_loaded) {
            self::add('<script>
                async function loaded() {
                    await new Promise(r => setTimeout(r, ' . $render->delay_after_load . '));
                    document.getElementsByTagName(\'html\')[0].style.opacity = 1;
                }
                window.addEventListener("DOMContentLoaded", (event) => {
                    loaded();
                });
            </script>');
            Debug::log('Définition de la fonction de rendu.');
        }

        self::add('</html>');
        Debug::log('HTML fermé.', Debug::LEVEL_GOOD);
    }
    

    /**
     * Formate les attributs d'un formulaire
     * 
     * @param string la methode HTTP
     * @param boolean si des fichiers sont envoyés
     * @param string la route de redirection
     * @param string les parametres de redirection (uniquement en methode POST)
     * @param string si on ajoute le parametre de retour
     * @return string le code HTML
     */
    static function setForm($method = 'GET', $isMultipart = false, $route = null, $param = [], $addback = false) {
        $action = $route !== null ? 
            Url::build($route, $param, $addback) :
            Url::current();
        $enctype = $isMultipart ? 'multipart/form-data' : '';
        return 
            self::setAttrib($action, 'action') . 
            self::setAttrib($enctype, 'enctype') . 
            self::setAttrib($method, 'method');
    }
    

    /**
     * Ajoute des attributs HTML
     * 
     * @param array les attributs [attribut => valeur]
     * @return string le code HTML
     */
    static function setAttribs($array) {
        $_ = '';
        foreach ($array as $name => $value) {
            $_ .= self::setAttrib($value, $name);
        }
        return $_;
    }


    /**
     * Cree un attribut HTML
     * 
     * @param string la balise HTML
     * @param array les attributs [attribut => valeur]
     * @param string le contenu de la balise
     * @param bool si la balise est une balise autofermante
     * @return string le code HTML
     */
    static function createElement($tag, $attr = null, $content = null, $selfClose = true) {
        $_ = '<' . $tag;
        if ($attr) {
            foreach ($attr as $key => $value) {
                $_ .= ' ' . $key . '="' . $value . '"';
            }
        }
        if ($content) {
            $_ .= '>' . $content . '</' . $tag . '>';
        } else {
            if ($selfClose) {
                $_ .= ' />';
            } else {
                $_ .= '></' . $tag . '>';
            }
        }
        return $_;
    }
    

    /**
     * Ajoute un attribut HTML
     * 
     * @param string valeur de l'attribut
     * @param string nom de l'attribut
     * @return string le code HTML
     */
    static function setAttrib($value, $name = 'value') {
        return $name . '="' . str_replace('"', '\\"', $value) . '"';
    }


    /**
     * Ajoute un lien href
     * 
     * @param string le lien
     * @return string le code HTML
     */
    static function setHref($link) {
        return self::setAttrib($link, 'href');
    }


    /**
     * Ajoute un id
     * 
     * @param string le lien
     * @return string le code HTML
     */
    static function setId($id) {
        return self::setAttrib($id, 'id');
    }


    /**
     * Ajoute une classe
     * 
     * @param string la classe
     * @return string le code HTML
     */
    static function setClass($class) {
        return self::setAttrib($class, 'class');
    }


    /**
     * Ajoute un ou des styles
     * 
     * @param string|array le/les style(s)
     * @return string le code HTML
     */
    static function setStyle($style) {
        if (is_array($style)) {
            $_ = '';
            foreach ($style as $s => $v) {
                $_ .= $s . ':' . $v . ';';
            }
            $style = $_;
        }
        return self::setAttrib($style, 'style');
    }


    /**
     * Ajoute une src
     * 
     * @param string la src
     * @param string le texte alternatif
     * @return string le code HTML
     */
    static function setSrc($src, $alt = null) {
        $html = self::setAttrib($src, 'src');
        if (!is_null($alt)) {
            $html .= ' ' . self::setAttrib($alt, 'alt');
        }
        return $html;
    }


    /**
     * Construit et ajoute un lien href
     * 
     * @param string la route
     * @param array les param
     * @param string le back
     * @return string le code HTML
     */
    static function buildHref($route, $param = [], $addback = false) {
        return self::setHref(Url::build($route, $param, $addback));
    }


    /**
     * Convertit une entree binaire en src base64
     *
     * @param object le binaire
     * @param string le texte alt
     * @param string le format de l'image
     * @return string le code HTML
     */
    static function binImgToSrcB64($bin, $alt = null, $format = 'png') {
        return self::setSrc(Image::binToB64($bin, $format), $alt);
    }
    

    /**
     * Defini la value si un get existe
     * 
     * @param string nom du parametre
     * @param string valeur par defaut
     * @param string propriete html
     * @return string le code HTML
     */
    static function getValue($name, $default = '', $key = 'value') {
        return self::setAttrib($_GET[$name] ?? $default, $key);
    }
    

    /**
     * Defini la value si un post existe
     * 
     * @param string nom du parametre
     * @param string valeur par defaut
     * @param string propriete html
     * @return string le code HTML
     */
    static function postValue($name, $default = '', $key = 'value') {
        return self::setAttrib($_POST[$name] ?? $default, $key);
    }
    

    /**
     * Importe un fichier javascript
     * 
     * @param string le fichier a importer
     * @param string le type de script
     * @param string le nom de la variable a instancier
     * @param string le nom de la classe a instancier
     * @return string le code HTML
     */
    static function importScript($file, $type = 'module', $name = null, $class = null) {
        if (Configuration::get()->render->use_minifying) {
            $inf = pathinfo($file);
            $file = $inf['dirname'] . '/' . $inf['filename'] . '.min.js';
        }
        if (is_null($name) && is_null($class)) {
            $js = '<script type="' . $type . '" src="' . $file . '"></script>';
        } else {
            $js = '<script type="' . $type . '">
                    import ' . $class . ' from "./' . $file . '";
                    window.' . $name . ' = new ' . $class .'();
                </script>';
        }
        return $js;
    }
    

    /**
     * Importe un fichier less
     * 
     * @param string le fichier a importer
     * @param string le type de ressource
     * @return string le code HTML
     */
    static function importStyle($file, $rel = 'stylesheet/less') {
        if (Configuration::get()->render->use_minifying) {
            $inf = pathinfo($file);
            $file = $inf['dirname'] . '/' . $inf['filename'] . '.min.css';
            $rel = 'stylesheet';
        }
        return '<link rel="' . $rel . '" type="text/css" href="' . $file . '">';
    }


    /**
     * Execute du code javascript
     * 
     * @param string le code javascript
     * @param string le type de script
     * @return string le code HTML
     */
    static function runScript($script, $type = 'module') {
        return '<script type="' . $type . '">' . $script . '</script>';
    }


    /**
     * Ajoute une balise style
     * 
     * @param string le code css
     * @return string le code HTML
     */
    static function addStyle($style) {
        return '<style>' . $style . '</style>';
    }


    /**
     * Envoi une alerte javascript
     * 
     * @param string le message
     * @return string le code HTML
     */
    static function alert($message) {
        return self::runScript('alert("' . str_replace('"', '\\"', $message) . '")');
    }


    /**
     * Ajoute du code HTML
     * 
     * @param string|array le code HTML
     * @return void
     */
    static function add($html) {
        if (is_array($html)) {
            $html = implode('', $html);
        }
        echo $html;
    }
    
}
