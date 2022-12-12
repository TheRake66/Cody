<?php
namespace Kernel\Url;

use Kernel\Debug\Log;
use Kernel\Html\Import;
use Kernel\Html\Builder;
use Kernel\Html\Javascript;
use Kernel\Html\Output;
use Kernel\Io\Stream;



/**
 * Librairie gérant la localisation de la page.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Url
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Location {

	/**
     * @var string Les méthodes HTTP.
	 */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';


	/**
	 * Accède à une URL.
	 * 
	 * @param string $url L'URL à accéder.
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
	 * Actualise la page.
	 * 
     * @return void
	 */
	static function reload() {
		self::change(Parser::current());
	}


	/**
	 * Accède à une URL dans l'application.
	 * 
	 * @param string $route La route à accéder.
	 * @param array $params Les paramètres à passer à la route.
	 * @param boolean $addBack Si on ajoute un lien pour revenir à la page précédente.
	 * @param string $method La méthode HTTP à utiliser.
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
	 * Contruit une URL.
	 * 
	 * @example build('/home', ['id' => 1, 'name' => 'toto'], true) => /home?id=1&name=toto&redirect_url=http%3A%2F%2Flocalhost%2Fhome
	 * @param string $route La route à accéder.
	 * @param array $params Les paramètres à passer à la route.
	 * @param boolean $addBack Si on ajoute un lien pour revenir à la page précédente.
	 * @return string L'URL construite.
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