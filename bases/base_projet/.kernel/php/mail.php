<?php
namespace Kernel;



/**
 * Librairie gerant les mails
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Mail {

	/**
	 * Envoi un mail
	 * 
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
	 * @param string|array le/les destinataires
	 * @param string l'objet du mail
	 * @param string le message du mail
	 * @param string|array le/les envoyeurs
	 * @param string|array repondre a
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
		self::implodeMails($headers, 'To', $to);
		self::implodeMails($headers, 'From', $from);
		self::implodeMails($headers, 'Reply-To', $reply);
		self::implodeMails($headers, 'Cc', $cc);
		self::implodeMails($headers, 'Bcc', $bcc);
		self::implodeHeaders($headers, $additional_headers);
        $message = str_replace('  ', ' ', $message);
        $message = wordwrap($message, 70, "\r\n", true);
        $headers = implode("\r\n", $headers);
		Debug::log('Envoi du mail (to : "' . print_r($to, true) . '", subject : "' . $subject . '")...', Debug::LEVEL_PROGRESS, Debug::TYPE_MAIL);
		Debug::log('Entête du mail : "' . print_r($headers, true) . '".', Debug::LEVEL_INFO, Debug::TYPE_MAIL_HEADER);
		Debug::log('Contenu du mail : "' . print_r($message, true) . '".', Debug::LEVEL_INFO, Debug::TYPE_MAIL_CONTENT);
		Error::remove();
		$response = mail($to, $subject, $message, $headers);
		Error::handler();
		if ($response) {
			Debug::log('Le mail a été accepté pour la livraison.', Debug::LEVEL_GOOD, Debug::TYPE_MAIL);
			return true;
		} else {
			Debug::log('Le mail n\'a pas été accepté pour la livraison !', Debug::LEVEL_ERROR, Debug::TYPE_MAIL);
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
	private static function implodeHeaders(&$headers, $additional_headers) {
		if (!is_null($additional_headers)) {
			if (is_array($additional_headers)) {
				if (Convert::isAssoc($additional_headers)) {
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
	private static function implodeMails(&$headers, $name, $mails) {
		if (!is_null($mails)) {
			$results = $name . ': ';
			if (is_array($mails)) {
				if (Convert::isAssoc($mails)) {
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
