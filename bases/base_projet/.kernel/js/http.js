import Url from './url.js';



/**
 * Librairie de communication via le protocole HTTP(S)
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Librairie
 */
export default class Http {

    /**
     * Les methodes d'envoie
     * 
     * @type {string}
     */
    static METHOD_GET = 'GET';
    static METHOD_POST = 'POST';
    

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
    static send(url, success = null, failed = null, expired = null, method = 'GET', params = {}, timeout = 0, asynchronous = true) {
        let xml = new XMLHttpRequest();
        if (method === Http.METHOD_GET && Object.keys(params).length !== 0) {
            url += '?' + (new URLSearchParams(params)).toString();
        }
        xml.open(method, url, asynchronous);
        if (timeout) xml.timeout = timeout;
        if (expired) xml.ontimeout = expired;
        if (failed) xml.onerror = failed;
        if (success) {
            xml.onload = () => {
                success(xml.response);
            }
        }
        if (method === Http.METHOD_POST) {
            let frm = new FormData();
            for (let name in params) {
                let value = params[name];
                frm.append(name, value);
            }
            xml.send(frm);
        } else {
            xml.send();
        }
    }

}