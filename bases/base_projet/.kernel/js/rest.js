import Http from './http.js';
import Url from './url.js';



/**
 * Librairie de communication avec l'API REST en PHP
 */
export default class Rest {
    
    /**
     * Execute une requete REST de type GET puis retourne le resultat
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} callback fonction anonyme appeler lors de la reponse
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec
     * @param {Array} param les parametres supplementaires a l'URL
     * @returns void
     */
    static get(route, rest, callback = null, empty = null, fail = null, param = {}) {
        return Rest.#ask(route, rest, callback, empty, fail, param, Http.METHOD_GET);
    }
    

    /**
     * Execute une requete REST de type POST puis retourne le resultat
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} callback fonction anonyme appeler lors de la reponse
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec
     * @param {Array} param les parametres supplementaires dans le corps de la requete
     * @returns void
     */
    static post(route, rest, callback = null, empty = null, fail = null, param = {}) {
        return Rest.#ask(route, rest, callback, empty, fail, param, Http.METHOD_POST);
    }

    
    /**
     * Execute une requete REST de type GET puis boucle sur les resultats
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} callback fonction anonyme appeler sur chaque reponse
     * @param {function} pre fonction anonyme appeler avant l'iteration
     * @param {function} post fonction anonyme appeler apres l'iteration
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec
     * @param {Array} param les parametres supplementaires a l'URL
     * @returns void
     */
    static getFor(route, rest, callback = null, pre = null, post = null, empty = null, fail = null, param = {}) {
        return Rest.#askFor(route, rest, callback, pre, post, empty, fail, param, Http.METHOD_GET);
    }

    
    /**
     * Execute une requete REST de type POST puis boucle sur les resultats
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} callback fonction anonyme appeler sur chaque reponse
     * @param {function} pre fonction anonyme appeler avant l'iteration
     * @param {function} post fonction anonyme appeler apres l'iteration
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec
     * @param {Array} param les parametres supplementaires dans le corps de la requete
     * @returns void
     */
    static postFor(route, rest, callback = null, pre = null, post = null, empty = null, fail = null, param = {}) {
        return Rest.#askFor(route, rest, callback, pre, post, empty, fail, param, Http.METHOD_POST);
    }


    /**
     * Execute une requete REST puis retourne le resultat
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} callback fonction anonyme appeler lors de la reponse
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {string} method la methode d'envoi
     * @returns void
     */
    static #ask(route, rest, callback = null, empty = null, fail = null, param = {}, method = Http.METHOD_GET) {
        let _ = {};
        _['routePage'] = route;
        _[rest] = true;
        param = Object.assign({}, _, param);

        Http.send(
            Url.root(),
            response => {
                if (response !== '') {
                    let json = null;
                    let continu = true;
                    try {
                        json = JSON.parse(response);
                    } catch (error) {
                        continu = false;
                    }
                    if (continu) {
                        if (callback) callback(json);
                    } else {
                        if (fail) fail();
                    }
                } else {
                    if (empty) empty();
                }
            },
            fail,
            method,
            param
        );
    }


    /**
     * Execute une requete REST puis boucle sur les resultats
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} callback fonction anonyme appeler sur chaque reponse
     * @param {function} pre fonction anonyme appeler avant l'iteration
     * @param {function} post fonction anonyme appeler apres l'iteration
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {string} method la methode d'envoi
     * @returns void
     */
    static #askFor(route, rest, callback = null, pre = null, post = null, empty = null, fail = null, param = {}, method = Http.METHOD_GET) {
        let _ = {};
        _['routePage'] = route;
        _[rest] = true;
        param = Object.assign({}, _, param);
        
        Http.send(
            Url.root(),
            response => {
                if (response !== '') {
                    let json = null;
                    let continu = true;
                    try {
                        json = JSON.parse(response);
                    } catch (error) {
                        continu = false;
                    }
                    if (continu) {
                        if (json.length > 0) {
                            if (pre) pre();
                            json.forEach(element => callback(element));
                            if (post) post();
                        } else {
                            if (empty) empty();
                        }
                    } else {
                        if (fail) fail();
                    }
                } else {
                    if (empty) empty();
                }
            },
            fail,
            method,
            param
        );
    }

}