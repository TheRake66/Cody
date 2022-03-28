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
     * @param {function} callback fonction anonyme appeler lors de la reponse correcte
     * @param {function} fail fonction anonyme appeler lors de la reponse en erreur
     * @param {string} method type de requete
     * @param {string} param corps de la requete
     * @returns void
     */
    static send(url, callback = null, fail = null, method = 'GET', param = null) {
        let xml = new XMLHttpRequest();
        xml.open(method, method === Http.METHOD_GET ? `${url}?${Url.objectToParam(param)}`: url, true);
        xml.onload = () => {
            if (callback) callback(xml.response);
        };
        xml.onerror = () => {
            if (fail) fail();
        };
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