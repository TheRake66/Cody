<?php
namespace Kernel\Communication;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\Io\Convert\Dataset;



/**
 * Librairie gerant les mails
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Communication
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Mail {

	/**
	 * Envoi un mail
	 * 
	 * @example
	 * Les mails peuvent etre envoyer de trois manieres :
	 * - string : 'test@test.com'
	 * - array : [ 'test@test.com', 'test@test.com', 'test@test.com' ]
	 * - array : [ 
	 * 		'nom' => 'test@test.com', 
	 * 		'nom' => 'test@test.com', 
	 * 		'nom' => 'test@test.com'
	 * ]
	 * 
	 * Les entete peuvent egalement etre envoyer de trois manieres :
	 * - string : 'Nom: valeur'
	 * - array : [ 'Nom: valeur', 'Nom: valeur', 'Nom: valeur' ]
	 * - array : [ 
	 * 		'Nom' => 'valeur',
	 * 		'Nom' => 'valeur',
	 * 		'Nom' => 'valeur'
	 * ]
	 * 
	 * A noter qu'accepter pour livraison ne veut pas dire qu'il arrivera a destination
	 * 
	 * @param string|array $to Le/les destinataire(s)
	 * @param string $subject L'objet du mail
	 * @param string $message Le message du mail
	 * @param string|array $from Le/les envoyeurs
	 * @param string|array $reply Le/les destinataire(s) de la 
	 * @param string|array le/les destinataires en copie
	 * @param string|array le/les destinataires en copie cachee
	 * @param bool si le message contient du HTML
	 * @param string|array le/les entetes additionnels
	 * @param array le/les destinataires en copie cachee
	 * @return bool si l'envoi a ete accepte pour livraison
	 */
	static function send($to, $subject, $message, $from = null, 
	$reply = null, $cc = null, $bcc = null, $is_html = false, 
	$additional_headers = null) {
		$headers = [ 'X-Mailer: PHP/' . phpversion() ];
		if ($is_html) {
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		} else {
			$headers[] = 'Content-type: text/plain; charset=utf-8';
		}
		self::mails($headers, 'To', $to);
		self::mails($headers, 'From', $from);
		self::mails($headers, 'Reply-To', $reply);
		self::mails($headers, 'Cc', $cc);
		self::mails($headers, 'Bcc', $bcc);
		self::headers($headers, $additional_headers);
        $message = str_replace('  ', ' ', $message);
        $message = wordwrap($message, 70, "\r\n", true);
        $headers = implode("\r\n", $headers);
		Log::add('Envoi du mail (to : "' . print_r($to, true) . '", subject : "' . $subject . '")...', Log::LEVEL_PROGRESS, Log::TYPE_MAIL);
		Log::add('Entête du mail : "' . print_r($headers, true) . '".', Log::LEVEL_INFO, Log::TYPE_MAIL_HEADER);
		Log::add('Contenu du mail : "' . print_r($message, true) . '".', Log::LEVEL_INFO, Log::TYPE_MAIL_CONTENT);
		Error::remove();
		$response = mail($to, $subject, $message, $headers);
		Error::handler();
		if ($response) {
			Log::add('Le mail a été accepté pour la livraison.', Log::LEVEL_GOOD, Log::TYPE_MAIL);
			return true;
		} else {
			Log::add('Le mail n\'a pas été accepté pour la livraison !', Log::LEVEL_ERROR, Log::TYPE_MAIL);
			return false;
		}
	}


	/**
	 * Convertit les trois differentes manieres d'envoyer des entetes au format mail :
	 * 
	 * @example
	 * - $additional_headers = 'Test: test'
	 * - $additional_headers = [ 'Test: test', 'Test: test', 'Test: test' ]
	 * - $additional_headers = [ 
	 * 		'Test' => 'test',
	 * 		'Test' => 'test',
	 * 		'Test' => 'test'
	 * ]
	 * 
	 * @param array les entetes existantes
	 * @param string|array le/les entetes additionnels
	 * @return void
	 */
	private static function headers(&$headers, $additional_headers) {
		if (!is_null($additional_headers)) {
			if (is_array($additional_headers)) {
				if (Dataset::assoc($additional_headers)) {
					$_ = [];
					foreach ($additional_headers as $key => $value) {
						$_[] = $key . ': ' . $value;
					}
					$additional_headers = $_;
				}
				$headers = array_merge($headers, $additional_headers);
			} else {
				$headers[] = $additional_headers;
			}
		}
	}


	/**
	 * Convertit les trois differentes manieres d'envoyer des mails au format mail :
	 * 
	 * @example
	 * Pour $name = 'To' :
	 * - $mails = 'test@test.com'
	 * - $mails = [ 'test@test.com', 'test@test.com', 'test@test.com' ]
	 * - $mails = [ 
	 * 		'nom' => 'test@test.com', 
	 * 		'nom' => 'test@test.com', 
	 * 		'nom' => 'test@test.com', 
	 * ]
	 * 
	 * Devient :
	 * - return 'To: test@test.com'
	 * - return 'To: test@test.com, test@test.com, test@test.com'
	 * - return 'To: nom <test@test.com>, nom <test@test.com>, nom <test@test.com>'
	 * 
	 * @param array les entetes existantes
	 * @param string le nom de l'entete
	 * @param string|array le/les mails
	 * @return void
	 */
	private static function mails(&$headers, $name, $mails) {
		if (!is_null($mails)) {
			$results = $name . ': ';
			if (is_array($mails)) {
				if (Dataset::assoc($mails)) {
					$_ = [];
					foreach ($mails as $name => $mail) {
						$_[] = $name . ' <' . $mail . '>';
					}
					$mails = $_;
				}
				$results .= implode(', ', $mails);
			} else {
				$results .= $mails;
			}
			$headers[] = $results;
		}
	}
}
