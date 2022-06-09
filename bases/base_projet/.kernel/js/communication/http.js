
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
     * @param {function} success Fonction anonyme appeler lors d'une réponse correcte.
     * @param {function} failed Fonction anonyme appeler lors d'une réponse incorrecte.
     * @param {function} expired Fonction anonyme appeler lorsque la requête expire.
     * @param {string} method Type de requête.
     * @param {string} params Corps de la requête. 
     * @param {Number} timeout Temps d'attente avant expiration de la requête.
     * @param {boolean} asynchronous Si la requête s'exécute en asynchrone.
     * @returns {void}
     */
    static send(url, success = null, failed = null, expired = null, method = Http.METHOD_GET, params = {}, timeout = 0, asynchronous = true) {
        let xhr = new XMLHttpRequest();
        if (method === Http.METHOD_GET && 
            params !== null &&
            Object.keys(params).length !== 0) {
            url += '?' + (new URLSearchParams(params)).toString();
        }
        xhr.open(method, url, asynchronous);
        if (timeout) xhr.timeout = timeout;
        if (expired) xhr.ontimeout = expired;
        if (failed) xhr.onerror = failed;
        if (success) {
            xhr.onload = () => {
                success(xhr.response);
            }
        }
        if (method !== Http.METHOD_GET) {
            let frm = new FormData();
            for (let name in params) {
                let value = params[name];
                frm.append(name, value);
            }
            xhr.send(frm);
        } else {
            xhr.send();
        }
    }

}