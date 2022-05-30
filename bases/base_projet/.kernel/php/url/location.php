<?php
namespace Kernel\URL;

use Kernel\Debug\Log;
use Kernel\HTML\Import;
use Kernel\HTML\Builder;
use Kernel\HTML\Javascript;
use Kernel\HTML\Output;
use Kernel\IO\Stream;


/**
 * Librairie gerant la localisation de la page
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\URL
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Location {

	/**
     * @var string les methodes d'envoie
	 */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';


	/**
	 * Accede a une url
	 * 
	 * @param string l'url
     * @return void
	 */
	static function change($url) {
		Log::add('Redirection vers l\'url : "'. $url .'".');
		Stream::clean();
		header('Location: ' . $url);
		Stream::close();
		exit;
	}
	

	/**
	 * Recharge la page
	 * 
     * @return void
	 */
	static function reload() {
		self::change(Parser::current());
	}


	/**
	 * Accede a une url dans l'application
	 * 
	 * @param string la route vers le composant
	 * @param array les parametres
	 * @param boolean si on ajoute le parametre de retour
	 * @param string la methode (GET, POST)
     * @return void
	 */
	static function go($route, $params = [], $addBack = false, $method = self::METHOD_GET) {
		if ($method == self::METHOD_GET) {
			self::change(self::build($route, $params, $addBack));
		} else {
			$html = Builder::create('form', [
				'action' => self::build($route),
				'method' => 'post',
				'id' => 'KERNEL_REDIRECT_FORM'
			]);
			if ($params) {				
				foreach ($params as $key => $value) {
					$html .= Builder::create('input', [
						'type' => 'hidden',
						'name' => $key,
						'value' => $value
					]);
				}
			}
			if ($addBack) {
				$html .= Builder::create('input', [
					'type' => 'hidden',
					'name' => 'redirect_url',
					'value' => Parser::current()
				]);
			}
			$html .= Javascript::run('
				let f = document.getElementById("KERNEL_REDIRECT_FORM");
				f.submit();
				f.remove();
			');
			Output::add($html);
		}
	}


	/**
	 * Contruit une url
	 * 
	 * @example build('/home', ['id' => 1, 'name' => 'toto'], true) => /home?id=1&name=toto&redirect_url=http%3A%2F%2Flocalhost%2Fhome
	 * @param string la route
	 * @param array les parametres
	 * @param string si on ajoute le parametre de retour
	 * @return string l'url
	 */
	static function build($route, $params = [], $addBack = false) {
		$url = Parser::root() . $route;
		if ($addBack) {
			$params['redirect_url'] = Parser::current();
		}
		if ($params || $addBack) {
			$url .= '?' . http_build_query($params);
		}
		return $url;
	}

}

?>