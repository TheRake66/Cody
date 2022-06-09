import Http from './Http.js';
import Location from '../url/location.js';



/**
 * Librairie de communication avec une API REST.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Rest {
    
    /**
     * Exécute une requête HTTP du type GET puis boucle sur les résultats.
     * 
     * @param {string} route La route.
     * @param {function} sucess Fonction anonyme appeler sur chaque réponse.
     * @param {function} pre Fonction anonyme appeler avant l'iteration.
     * @param {function} post Fonction anonyme appeler après l'iteration.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {Array} param Les paramètres supplémentaires à l'URL.
     * @param {Number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchrone.
     * @returns {void}
     */
    static getFor(route, sucess = null, pre = null, post = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        Rest.#askFor(route, sucess, pre, post, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_GET);
    }

    
    /**
     * Exécute une requête HTTP du type GET puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {Array} param Les paramètres supplémentaires à l'URL.
     * @param {Number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchrone.
     * @returns {void}
     */
    static get(route, sucess = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        Rest.#ask(route, sucess, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_GET);
    }
    

    /**
     * Exécute une requête HTTP du type POST puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {Array} param Les paramètres supplémentaires au corps de la requête.
     * @param {Number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchrone.
     * @returns {void}
     */
    static post(route, sucess = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        Rest.#ask(route, sucess, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_POST);
    }
    

    /**
     * Exécute une requête HTTP du type PUT puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {Array} param Les paramètres supplémentaires au corps de la requête.
     * @param {Number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchrone.
     * @returns {void}
     */
    static put(route, sucess = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        Rest.#ask(route, sucess, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_PUT);
    }
    

    /**
     * Exécute une requête HTTP du type DELETE puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {Array} param Les paramètres supplémentaires au corps de la requête.
     * @param {Number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchrone.
     * @returns {void}
     */
    static delete(route, sucess = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        Rest.#ask(route, sucess, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_DELETE);
    }
    

    /**
     * Exécute une requête HTTP du type PATCH puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {Array} param Les paramètres supplémentaires au corps de la requête.
     * @param {Number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchrone.
     * @returns {void}
     */
    static patch(route, sucess = null, empty = null, failed = null, expired = null, param = {}, timeout = 0, asynchrone = true) {
        Rest.#ask(route, sucess, empty, failed, expired, param, timeout, asynchrone, Http.METHOD_PATCH);
    }


    /**
     * Exécute une requête puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {Array} param Les paramètres supplémentaires au corps de la requête ou à l'URL.
     * @param {string} method La méthode de la requête.
     * @param {Number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchrone.
     * @returns {void}
     */
    static #ask(route, sucess, empty, failed, expired, param, timeout, asynchrone, method) {
        Http.send(
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
                            if (sucess) sucess(json.content, json);
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
     * Exécute une requête puis boucle sur les résultats.
     * 
     * @param {string} route La route.
     * @param {function} sucess Fonction anonyme appeler sur chaque réponse.
     * @param {function} pre Fonction anonyme appeler avant l'iteration.
     * @param {function} post Fonction anonyme appeler après l'iteration.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {Array} param Les paramètres supplémentaires au corps de la requête ou à l'URL.
     * @param {string} method La méthode de la requête.
     * @param {Number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchrone.
     * @returns {void}
     */
    static #askFor(route, sucess, pre, post, empty, failed, expired, param, timeout, asynchrone, method) {
        Http.send(
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
                            json.content.forEach(element => sucess(element, json));
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