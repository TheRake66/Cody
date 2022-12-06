
/**
 * Librairie de communication via le protocole HTTP(S).
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Http {

	/**
	 * @type {int} Code de retour HTTP.
	 */
	static HTTP_CONTINUE = 100;
	static HTTP_SWITCHING_PROTOCOLS = 101;
	static HTTP_PROCESSING = 102;
	static HTTP_EARLY_HINTS = 103;
	static HTTP_OK = 200;
	static HTTP_CREATED = 201;
	static HTTP_ACCEPTED = 202;
	static HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
	static HTTP_NO_CONTENT = 204;
	static HTTP_RESET_CONTENT = 205;
	static HTTP_PARTIAL_CONTENT = 206;
	static HTTP_MULTI_STATUS = 207;
	static HTTP_ALREADY_REPORTED = 208;
	static HTTP_CONTENT_DIFFERENT = 210;
	static HTTP_IM_USED = 226;
	static HTTP_MULTIPLE_CHOICES = 300;
	static HTTP_MOVED_PERMANENTLY = 301;
	static HTTP_FOUND = 302;
	static HTTP_SEE_OTHER = 303;
	static HTTP_NOT_MODIFIED = 304;
	static HTTP_USE_PROXY = 305;
	static HTTP_RESERVED = 306;
	static HTTP_TEMPORARY_REDIRECT = 307;
	static HTTP_PERMANENTLY_REDIRECT = 308;
	static HTTP_BAD_REQUEST = 400;
	static HTTP_UNAUTHORIZED = 401;
	static HTTP_PAYMENT_REQUIRED = 402;
	static HTTP_FORBIDDEN = 403;
	static HTTP_NOT_FOUND = 404;
	static HTTP_METHOD_NOT_ALLOWED = 405;
	static HTTP_NOT_ACCEPTABLE = 406;
	static HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
	static HTTP_REQUEST_TIMEOUT = 408;
	static HTTP_CONFLICT = 409;
	static HTTP_GONE = 410;
	static HTTP_LENGTH_REQUIRED = 411;
	static HTTP_PRECONDITION_FAILED = 412;
	static HTTP_PAYLOAD_TOO_LARGE = 413;
	static HTTP_URI_TOO_LONG = 414;
	static HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
	static HTTP_RANGE_NOT_SATISFIABLE = 416;
	static HTTP_EXPECTATION_FAILED = 417;
	static HTTP_IM_A_TEAPOT = 418;
	static HTTP_MISDIRECTED_REQUEST = 421;
	static HTTP_UNPROCESSABLE_ENTITY = 422;
	static HTTP_LOCKED = 423;
	static HTTP_FAILED_DEPENDENCY = 424;
	static HTTP_TOO_EARLY = 425;
	static HTTP_UPGRADE_REQUIRED = 426;
	static HTTP_PRECONDITION_REQUIRED = 428;
	static HTTP_TOO_MANY_REQUESTS = 429;
	static HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
	static HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
	static HTTP_INTERNAL_SERVER_ERROR = 500;
	static HTTP_NOT_IMPLEMENTED = 501;
	static HTTP_BAD_GATEWAY = 502;
	static HTTP_SERVICE_UNAVAILABLE = 503;
	static HTTP_GATEWAY_TIMEOUT = 504;
	static HTTP_VERSION_NOT_SUPPORTED = 505;
	static HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;
	static HTTP_INSUFFICIENT_STORAGE = 507;
	static HTTP_LOOP_DETECTED = 508;
	static HTTP_BANDWIDTH_LIMIT_EXCEEDED = 509;
	static HTTP_NOT_EXTENDED = 510;
	static HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;


    /**
     * @type {string} Les méthodes HTTP.
     */
	static METHOD_GET = 'GET';
	static METHOD_POST = 'POST';
	static METHOD_PUT = 'PUT';
	static METHOD_DELETE = 'DELETE';
	static METHOD_HEAD = 'HEAD';
	static METHOD_OPTIONS = 'OPTIONS';
	static METHOD_PATCH = 'PATCH';
    

    /**
     * Exécute une requête AJAX.
     * 
     * @param {string} url URL à requêter.
     * @param {string} type Type de contenu à envoyer.
     * @param {function} success Fonction anonyme appeler lors d'une réponse correcte.
     * @param {function} failed Fonction anonyme appeler lors d'une réponse incorrecte.
     * @param {function} expired Fonction anonyme appeler lorsque la requête expire.
     * @param {string} method Type de requête.
     * @param {any} body Corps de la requête. 
     * @param {Number} timeout Temps d'attente avant expiration de la requête.
     * @param {boolean} asynchronous Si la requête s'exécute en asynchrone.
     * @returns {void}
     */
    static send(url, type = 'text/html; charset=utf-8', success = null, failed = null, expired = null, method = Http.METHOD_GET, body = null, timeout = 0, asynchronous = true) {
        let xhr = new XMLHttpRequest();
        xhr.open(method, url, asynchronous);
        xhr.setRequestHeader('Content-Type', type);
        if (timeout) xhr.timeout = timeout;
        if (expired) xhr.ontimeout = expired;
        if (failed) xhr.onerror = failed;
        if (success) {
            xhr.onload = () => {
                success(xhr.response);
            }
        }
        if (Array.isArray(body) || (body instanceof Object)) {
            let frm = new FormData();
            for (let name in body) {
                let value = body[name];
                frm.append(name, value);
            }
            xhr.send(frm);
        } else {
            xhr.send(body);
        }
    }

}