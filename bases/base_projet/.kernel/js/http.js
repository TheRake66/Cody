import Url from './url.js';



/**
 * Librairie de communication via le protocole HTTP(S)
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
     * @param {string} param corps de la requete 
     * @param {Number} timeout temps d'attente avant expiration de la requete
     * @returns void
     */
    static send(url, success = null, failed = null, expired = null, method = 'GET', param = null, timeout = null) {
        let xml = new XMLHttpRequest();
        xml.open(method, method === Http.METHOD_GET ? `${url}?${Url.objectToParam(param)}`: url, false);
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
            for (let name in param) {
                let value = param[name];
                frm.append(name, value);
            }
            xml.send(frm);
        } else {
            xml.send();
        }
    }

}