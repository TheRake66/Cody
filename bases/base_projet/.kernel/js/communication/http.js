
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
     * @type {string} Les méthodes d'envoi.
     */
    static METHOD_GET = 'GET';
    static METHOD_POST = 'POST';
    static METHOD_PUT = 'PUT';
    static METHOD_DELETE = 'DELETE';
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