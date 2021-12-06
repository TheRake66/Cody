<?php
// Librairie Html
namespace Kernel;



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
	
}
