import Url from './url.js';



// Librairie Http
export default class Http {

    /**
     * Execute une requete http(s) en async
     * 
     * @param {string} url URL a requeter
     * @param {function} callback fonction anonyme appeler lors de la reponse correcte
     * @param {function} fail fonction anonyme appeler lors de la reponse en erreur
     * @param {string} type type de requete
     * @param {string} body corps de la requete
     */
    static send(url, callback = null, fail = null, type = 'GET', body = null) {
        let xml = new XMLHttpRequest();
        xml.open(type, url, true);
        xml.onload = () => {
            if (callback) callback(xml.response);
        };
        xml.onerror = () => {
            if (fail) fail();
        };
        xml.send(body);
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
                let json = null;
                let continu = true;
                try {
                    json = JSON.parse(response);
                } catch (error) {
                    console.error(error);
                    continu = false;
                    if (fail) fail();
                }
                if (continu) {
                    if (json) {
                        if (callback) callback(json);
                    } else {
                        if (empty) empty();
                    }
                }
            },
            fail
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
                let json = null;
                let continu = true;
                try {
                    json = JSON.parse(response);
                } catch (error) {
                    console.error(error);
                    continu = false;
                    if (fail) fail();
                }
                if (continu) {
                    if (json && json.length > 0) {
                        if (pre) pre();
                        json.forEach(element => callback(element));
                        if (post) post();
                    } else {
                        if (empty) empty();
                    }
                }
            },
            fail
        );
    }

}