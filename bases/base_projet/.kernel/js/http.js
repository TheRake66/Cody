import Url from './url.js';



// Librairie Http
export default class Http {
    
    static reqType = {
        GET: "GET",
        POST: "POST"
    }

    /**
     * Execute une requete http(s) en async
     * 
     * @param {string} url URL a requeter
     * @param {function} callback fonction anonyme appeler lors de la reponse
     * @param {ReqType} type type de requete
     */
    static send(url, callback, type = Http.reqType.GET) {
        let xml = new XMLHttpRequest();
        xml.open(type, url, true);
        xml.onreadystatechange = () => {
            if (xml.status == 200 && xml.readyState == 4)
                callback(xml.response);
        }
        xml.send();
    }


    /**
     * Execute une fonction Ajax
     * 
     * @param {string} route la route
     * @param {string} ajax le nom du parametre Ajax
     * @param {function} callback fonction anonyme appeler lors de la reponse
     * @param {Array} param les parametres supplementaires a l'URL
     */
    static ajax(route, ajax, callback, param = {}) {
        let _ = {};
        _[ajax] = true;
        Http.send(
            Url.build(route, Object.assign({}, _, param)),
            response => {
                let j = JSON.parse(response);
                if (j !== null) {
                    callback(j);
                }
            }
        );
    }


    /**
     * Execute une fonction Ajax sur plusieurs reponses
     * 
     * @param {string} route la route
     * @param {string} ajax le nom du parametre Ajax
     * @param {function} callback fonction anonyme appeler lors de la reponse
     * @param {Array} param les parametres supplementaires a l'URL
     */
    static ajaxForEach(route, ajax, callback, param = {}) {
        let _ = {};
        _[ajax] = true;
        Http.send(
            Url.build(route, Object.assign({}, _, param)),
            response => {
                let j = JSON.parse(response);
                if (j !== null) {
                    j.forEach(element => {
                        callback(element);
                    });
                }
            }
        );
    }

}