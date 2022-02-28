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
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec de Ajax
     * @param {Array} param les parametres supplementaires a l'URL
     */
    static ajax(route, ajax, callback = null, empty = null, fail = null, param = {}) {
        let _ = {};
        _[ajax] = true;
        Http.send(
            Url.build(route, Object.assign({}, _, param)),
            response => {
                try {
                    let j = JSON.parse(response);
                    if (j !== null) {
                        if (callback !== null) callback(j);
                    } else {
                        if (empty !== null) empty();
                    }
                } catch (error) {
                    console.log(error);
                    if (fail !== null) fail();
                }
            }
        );
    }


    /**
     * Execute une fonction Ajax sur plusieurs reponses
     * 
     * @param {string} route la route
     * @param {string} ajax le nom du parametre Ajax
     * @param {function} callback fonction anonyme appeler sur chaque reponse
     * @param {function} pre fonction anonyme appeler avant l'iteration
     * @param {function} post fonction anonyme appeler apres l'iteration
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec de Ajax
     * @param {Array} param les parametres supplementaires a l'URL
     */
    static ajaxForEach(route, ajax, callback = null, pre = null, post = null, empty = null, fail = null, param = {}) {
        let _ = {};
        _[ajax] = true;
        Http.send(
            Url.build(route, Object.assign({}, _, param)),
            response => {
                try {
                    let j = JSON.parse(response);
                    if (j !== null && j.length > 0) {
                        if (pre !== null) pre();
                        j.forEach(element => {
                            callback(element);
                        });
                        if (post !== null) post();
                    } else {
                        if (empty !== null) empty();
                    }
                } catch (error) {
                    console.log(error);
                    if (fail !== null) fail();
                }
            }
        );
    }

}