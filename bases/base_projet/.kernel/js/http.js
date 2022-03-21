import Url from './url.js';



// Librairie Http
export default class Http {

    /**
     * Les methodes d'envoie
     */
    static METHOD_GET = 'GET';
    static METHOD_POST = 'POST';
    

    /**
     * Execute une requete http(s) en async
     * 
     * @param {string} url URL a requeter
     * @param {function} callback fonction anonyme appeler lors de la reponse correcte
     * @param {function} fail fonction anonyme appeler lors de la reponse en erreur
     * @param {string} method type de requete
     * @param {string} param corps de la requete
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


    /**
     * Execute une fonction Ajax
     * 
     * @param {string} route la route
     * @param {string} ajax le nom du parametre Ajax
     * @param {function} callback fonction anonyme appeler lors de la reponse
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec de Ajax
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {string} method la methode d'envoi
     */
    static ajax(route, ajax, callback = null, empty = null, fail = null, param = {}, method = Http.METHOD_GET) {
        let _ = {};
        _['r'] = route;
        _[ajax] = true;
        param = Object.assign({}, _, param);

        Http.send(
            '/index.php',
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
                    if (json !== null) {
                        if (callback) callback(json);
                    } else {
                        if (empty) empty();
                    }
                }
            },
            fail,
            method,
            param
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
     * @param {string} method la methode d'envoi
     */
    static ajaxForEach(route, ajax, callback = null, pre = null, post = null, empty = null, fail = null, param = {}, method = Http.METHOD_GET) {
        let _ = {};
        _['r'] = route;
        _[ajax] = true;
        param = Object.assign({}, _, param);

        Http.send(
            '/index.php',
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
                    if (json !== null && json.length > 0) {
                        if (pre) pre();
                        json.forEach(element => callback(element));
                        if (post) post();
                    } else {
                        if (empty) empty();
                    }
                }
            },
            fail,
            method,
            param
        );
    }

}