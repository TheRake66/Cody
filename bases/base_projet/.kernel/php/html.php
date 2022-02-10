<?php
// Librairie Html
namespace Kernel;
use Kernel\Url;



class Html {

	/**
	 * Ajoute un attribut HTML
	 * 
	 * @param string valeur de l'attribut
	 * @param string nom de l'attribut
	 * @return string l'attribut formatte
	 */
	static function setAttrib($value, $name = 'value') {
		return $name . '="' . str_replace('"', '\\"', $value) . '"';
	}


	/**
	 * Ajoute un lien HREF
	 * 
	 * @param string le lien
	 * @return string l'attribut formatte
	 */
	static function setHref($link) {
		return Html::setAttrib($link, 'href');
	}


	/**
	 * Construit et ajoute un lien HREF
	 * 
	 * @param string la route
	 * @param array les param
	 * @param string le back
	 * @return string l'attribut formatte
	 */
	static function buildHref($route, $param = [], $addback = false) {
		return Html::setHref(Url::build($route, $param, $addback));
	}
	

	/**
	 * Defini la value si un get existe
	 * 
	 * @param string nom du parametre
	 * @param string valeur par defaut
	 * @param string propriete html
	 * @return string la valeur
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
	 * @return string la valeur
	 */
	static function postValue($name, $default = '', $key = 'value') {
		return self::setAttrib($_POST[$name] ?? $default, $key);
	}
	

	/**
	 * Importe un fichier javascript
	 * 
	 * @param string le fichier a importer
	 * @param string le nom de la variable a instancier
	 * @param string le nom de la classe a instancier
	 * @return string le code HTML qui importe le script
	 */
	static function importScript($file, $name = null, $class = null) {
		$js = '<script type="text/javascript" src="' . $file . '"></script>';
		if (!is_null($name) && !is_null($class)) {
			$js .= '<script>const ' . $name . ' = new ' . $class .'();</script>';
		}
        return $js;
	}
	

	/**
	 * Importe un fichier less
	 * 
	 * @param string le fichier a importer
	 * @return string le code HTML qui importe le style
	 */
	static function importStyle($file) {
        return '<link rel="stylesheet/less" type="text/css" href="' . $file . '">';
	}
	
}
