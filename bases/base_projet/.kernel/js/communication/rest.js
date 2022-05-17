import HTTP from './http.js';
import Location from '../url/location.js';



/**
 * Librairie de communication avec l'API REST en PHP
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Rest {
    
    /**
     * Execute une requete REST de type GET puis retourne le resultat
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} sucess fonction anonyme appeler lors de la reponse
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} failed fonction anonyme appeler si echec
     * @param {function} expired fonction anonyme appeler si temps d'attente depasse
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {Number} timeout le temps d'attente avant echec
     * @param {boolean} asynchronous si la requete s'execute en asynchrone
     * @returns {void}
     */
    static get(route, rest, sucess = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        return Rest.#ask(route, rest, sucess, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_GET);
    }
    

    /**
     * Execute une requete REST de type POST puis retourne le resultat
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} sucess fonction anonyme appeler lors de la reponse
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} failed fonction anonyme appeler si echec
     * @param {function} expired fonction anonyme appeler si temps d'attente depasse
     * @param {Array} param les parametres supplementaires dans le corps de la requete
     * @param {Number} timeout le temps d'attente avant echec
     * @param {boolean} asynchronous si la requete s'execute en asynchrone
     * @returns {void}
     */
    static post(route, rest, sucess = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        return Rest.#ask(route, rest, sucess, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_POST);
    }

    
    /**
     * Execute une requete REST de type GET puis boucle sur les resultats
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} sucess fonction anonyme appeler sur chaque reponse
     * @param {function} pre fonction anonyme appeler avant l'iteration
     * @param {function} post fonction anonyme appeler apres l'iteration
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} failed fonction anonyme appeler si echec
     * @param {function} expired fonction anonyme appeler si temps d'attente depasse
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {Number} timeout le temps d'attente avant echec
     * @param {boolean} asynchronous si la requete s'execute en asynchrone
     * @returns {void}
     */
    static getFor(route, rest, sucess = null, pre = null, post = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        return Rest.#askFor(route, rest, sucess, pre, post, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_GET);
    }

    
    /**
     * Execute une requete REST de type POST puis boucle sur les resultats
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} sucess fonction anonyme appeler sur chaque reponse
     * @param {function} pre fonction anonyme appeler avant l'iteration
     * @param {function} post fonction anonyme appeler apres l'iteration
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} failed fonction anonyme appeler si echec
     * @param {function} expired fonction anonyme appeler si temps d'attente depasse
     * @param {Array} param les parametres supplementaires dans le corps de la requete
     * @param {Number} timeout le temps d'attente avant echec
     * @param {boolean} asynchronous si la requete s'execute en asynchrone
     * @returns {void}
     */
    static postFor(route, rest, sucess = null, pre = null, post = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        return Rest.#askFor(route, rest, sucess, pre, post, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_POST);
    }


    /**
     * Execute une requete REST puis retourne le resultat
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} sucess fonction anonyme appeler lors de la reponse
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} failed fonction anonyme appeler si echec
     * @param {function} expired fonction anonyme appeler si temps d'attente depasse
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {string} method la methode d'envoi
     * @param {Number} timeout le temps d'attente avant echec
     * @param {boolean} asynchronous si la requete s'execute en asynchrone
     * @returns {void}
     */
    static #ask(route, rest, sucess = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true, method = Http.METHOD_GET) {
        if (param === null) {
            param = {};
        }
        param.rest_function = rest;
        HTTP.send(
            Location.build(route),
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
                        if (json.content !== null) {
                            if (sucess) sucess(json);
                        } else if (json.code === 0) {
                            if (empty) empty(json);
                        } else {
                            if (failed) failed(json);
                        }
                    } else {
                        if (failed) failed();
                    }
                } else {
                    if (failed) failed();
                }
            },
            failed,
            expired,
            method,
            param,
            timeout,
            asynchrone
        );
    }


    /**
     * Execute une requete REST puis boucle sur les resultats
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} sucess fonction anonyme appeler sur chaque reponse
     * @param {function} pre fonction anonyme appeler avant l'iteration
     * @param {function} post fonction anonyme appeler apres l'iteration
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} failed fonction anonyme appeler si echec
     * @param {function} expired fonction anonyme appeler si temps d'attente depasse
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {string} method la methode d'envoi
     * @param {Number} timeout le temps d'attente avant echec
     * @param {boolean} asynchronous si la requete s'execute en asynchrone
     * @returns {void}
     */
    static #askFor(route, rest, sucess = null, pre = null, post = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true, method = Http.METHOD_GET) {
        if (param === null) {
            param = {};
        }
        param.rest_function = rest;
        HTTP.send(
            Location.build(route),
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
                        if (json.content !== null && json.content.length > 0) {
                            if (pre) pre(json);
                            json.forEach(element => sucess(json, element));
                            if (post) post(json);
                        } else if (json.code === 0) {
                            if (empty) empty(json);
                        } else {
                            if (failed) failed(json);
                        }
                    } else {
                        if (failed) failed();
                    }
                } else {
                    if (empty) empty();
                }
            },
            failed,
            expired,
            method,
            param,
            timeout,
            asynchrone
        );
    }

}