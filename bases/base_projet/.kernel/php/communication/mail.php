<?php

namespace Kernel\Communication;

use Kernel\Debug\Error;
use Kernel\Debug\Log;
use Kernel\Io\Convert\Dataset;



/**
 * Librairie gérant les envois de mail.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @since Cody 7(21.65.0)
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 * @license MIT License
 * @package Kernel\Communication
 * @category Framework source
 * @abstract
 */
abstract class Mail {

	/**
	 * Envoie un mail.
	 * 
	 * Exemple : send([
	 *     "Alice" => "alice@foo.bar",
	 *     "Bob" => "bob@foo.bar"
	 * ], "My mail", "Hello my friends !", "me@foo.bar");
	 * 
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
	 * À noter qu'accepter pour livraison ne veut pas dire qu'il arrivera à destination.
	 * 
	 * @access public
	 * @static
	 * @param string|array $to Le/les destinataire(s).
	 * @param string $subject L'objet du mail.
	 * @param string $message Le message du mail.
	 * @param string|array $from [optional] [default = null] Le/les envoyeurs.
	 * @param string|array $reply [optional] [default = null] Le/les destinataire(s).
	 * @param string|array $cc [optional] [default = null] Le/les destinataires en copie.
	 * @param string|array $bcc [optional] [default = null] Le/les destinataires en copie cachée.
	 * @param bool $is_html [optional] [default = false] Si le message contient du HTML.
	 * @param string|array $additional_headers [optional] [default = null] Le/les entêtes additionnels.
	 * @return bool Si l'envoi a été accepté pour livraison.
	 */
	static function send(
		$to, 
		$subject, 
		$message, 
		$from = null, 
		$reply = null, 
		$cc = null, 
		$bcc = null, 
		$is_html = false, 
		$additional_headers = null
	) {
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
        $headers = implode("\r\n", $headers);
        $message = str_replace('  ', ' ', $message);
        $message = wordwrap($message, 70, "\r\n", true);
		Log::progress('Envoi du mail (to : "' . print_r($to, true) . '", subject : "' . $subject . '")...', Log::TYPE_MAIL);
		Log::info('Entête du mail : "' . print_r($headers, true) . '".', Log::TYPE_MAIL_HEADER);
		Log::info('Contenu du mail : "' . print_r($message, true) . '".', Log::TYPE_MAIL_CONTENT);
		Error::remove();
		$response = mail($to, $subject, $message, $headers);
		Error::handler();
		if ($response) {
			Log::good('Le mail a été accepté pour la livraison.', Log::TYPE_MAIL);
			return true;
		} else {
			Log::error('Le mail n\'a pas été accepté pour la livraison !', Log::TYPE_MAIL);
			return false;
		}
	}


	/**
	 * Convertis les trois différentes manières d'envoyer des entêtes au format mail.
	 * 
	 * @access private
	 * @static
	 * @param array &$headers Les entêtes.
	 * @param string|array $additional_headers Les entêtes additionnels.
	 * @return void
	 */
	private static function headers(
		&$headers, 
		$additional_headers
	) {
		if (!is_null($additional_headers)) {
			if (is_array($additional_headers)) {
				if (Dataset::assoc($additional_headers)) {
					$_ = [];
					foreach ($additional_headers as $key => $value) {
						$_[] = "$key: $value";
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
	 * Convertis les trois différentes manières d'envoyer des mails au format mail.
	 * 
	 * @access private
	 * @static
	 * @param array &$headers Les entêtes.
	 * @param string $name Le nom de l'entête.
	 * @param string|array $mails Les mails.
	 * @return void
	 */
	private static function mails(
		&$headers, 
		$name, 
		$mails
	) {
		if (!is_null($mails)) {
			$results = "$name: ";
			if (is_array($mails)) {
				if (Dataset::assoc($mails)) {
					$_ = [];
					foreach ($mails as $name => $mail) {
						$_[] = $name . " <$mail>";
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

?>