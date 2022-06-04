
/**
 * Librairie de communication via le protocole HTTP(S)
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Http {

    /**
     * @type {string} les methodes d'envoi
     */
    static METHOD_GET = 'GET';
    static METHOD_POST = 'POST';
    static METHOD_PUT = 'PUT';
    static METHOD_DELETE = 'DELETE';
    static METHOD_PATCH = 'PATCH';
    

    /**
     * Execute une requete AJAX en async
     * 
     * @param {string} url URL a requeter
     * @param {function} success fonction anonyme appeler lors de la reponse correcte
     * @param {function} failed fonction anonyme appeler lors de la reponse en erreur
     * @param {function} expired fonction anonyme appeler lorsque la requete expire
     * @param {string} method type de requete
     * @param {string} params corps de la requete 
     * @param {Number} timeout temps d'attente avant expiration de la requete
     * @param {boolean} asynchronous si la requete s'execute en asynchrone
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