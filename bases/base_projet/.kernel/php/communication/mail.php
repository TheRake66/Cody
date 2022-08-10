<?php
namespace Kernel\Communication;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\Io\Convert\Dataset;



/**
 * Librairie gérant les envois de mail.
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
	 * Envoie un mail.
	 * 
	 * @example
	 * Les mails peuvent être envoyé de trois manières :
	 * - string : 'test@test.com'
	 * - array : [ 'test@test.com', 'test@test.com', 'test@test.com' ]
	 * - array : [ 
	 * 		'nom' => 'test@test.com', 
	 * 		'nom' => 'test@test.com', 
	 * 		'nom' => 'test@test.com'
	 * ]
	 * 
	 * Les entêtes peuvent également être envoyé de trois manières :
	 * - string : 'Nom: valeur'
	 * - array : [ 'Nom: valeur', 'Nom: valeur', 'Nom: valeur' ]
	 * - array : [ 
	 * 		'Nom' => 'valeur',
	 * 		'Nom' => 'valeur',
	 * 		'Nom' => 'valeur'
	 * ]
	 * 
	 * À noter qu'accepter pour livraison ne veut pas dire qu'il arrivera a destination.
	 * 
	 * @param string|array $to Le/les destinataire(s).
	 * @param string $subject L'objet du mail.
	 * @param string $message Le message du mail.
	 * @param string|array $from Le/les envoyeurs.
	 * @param string|array $reply Le/les destinataire(s).
	 * @param string|array $cc Le/les destinataires en copie.
	 * @param string|array $bcc Le/les destinataires en copie cachée.
	 * @param bool $is_html Si le message contient du HTML.
	 * @param string|array $additional_headers Le/les entêtes additionnels.
	 * @return bool Si l'envoi a été accepté pour livraison.
	 */
	static function send($to, $subject, $message, $from = null, 
	$reply = null, $cc = null, $bcc = null, $is_html = false, 
	$additional_headers = null) {
		$headers = [ 'X-Mailer: PHP/' . phpversion() ];
		if ($is_html) {
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=utf-8';
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
	 * Convertis les trois différentes manières d'envoyer des entêtes au format mail :
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
	 * @param array $headers Les entêtes.
	 * @param string|array $additional_headers Les entêtes additionnels.
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
	 * Convertis les trois différentes manières d'envoyer des mails au format mail :
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
	 * @param array $headers Les entêtes.
	 * @param string $name Le nom de l'entête.
	 * @param string|array $mails Les mails.
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
