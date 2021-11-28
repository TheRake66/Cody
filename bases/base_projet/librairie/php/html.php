<?php

namespace Librairie;



class HTML {

	/**
	 * Ajoute un attribut HTML
	 * 
	 * @param string valeur de l'attribut
	 * @param string nom de l'attribut
	 * @return string l'attribut formatte
	 */
	public static function setAttrib($value, $name = 'value') {
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
	public static function getValue($name, $default = '', $key = 'value') {
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
	public static function postValue($name, $default = '', $key = 'value') {
		return self::setAttrib($_POST[$name] ?? $default, $key);
	}
	
}
