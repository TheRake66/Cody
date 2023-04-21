import Http from './http.js';
import Location from '../url/location.js';



/**
 * Librairie de communication avec une API REST.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.1.0.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
export default class Rest {

    /**
     * @var {array} loading Les requêtes qui sont en attente.
     */
    static #loading = [];

    
    /**
     * Exécute une requête HTTP du type GET puis boucle sur les résultats.
     * 
     * @param {string} route La route interne de l'application.
     * @param {function} sucess Fonction anonyme appeler sur chaque réponse.
     * @param {function} before Fonction anonyme appeler avant l'iteration.
     * @param {function} after Fonction anonyme appeler après l'iteration.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {array} parameters Les paramètres supplémentaires à l'URL.
     * @param {number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchronous.
     * @param {boolean} once Si une seule requête doit être exécuté pour la même route.
     * @param {boolean} stack Si la requête doit attendre que la précédente se termine ou si elle est ignorée.
     * @returns {void}
     */
    static getFor(route, sucess = null, before = null, after = null, empty = null, failed = null, expired = null, parameters = {}, timeout = 0, asynchronous = true, once = false, stack = false) {
        Rest.#manage(
            Location.build(route, parameters),
            Http.METHOD_GET,
            true,
            route,
            sucess,
            before,
            after,
            empty,
            failed,
            expired,
            null, 
            timeout,
            asynchronous,
            once,
            stack);
    }

    
    /**
     * Exécute une requête HTTP du type GET puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route interne de l'application.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {array} parameters Les paramètres supplémentaires à l'URL.
     * @param {number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchronous.
     * @param {boolean} once Si une seule requête doit être exécuté pour la même route.
     * @param {boolean} stack Si la requête doit attendre que la précédente se termine ou si elle est ignorée.
     * @returns {void}
     */
    static get(route, sucess = null, empty = null, failed = null, expired = null, parameters = {}, timeout = 0, asynchronous = true, once = false, stack = false) {
        Rest.#manage(
            Location.build(route, parameters),
            Http.METHOD_GET,
            false,
            route,
            sucess,
            null,
            null,
            empty,
            failed,
            expired,
            null, 
            timeout,
            asynchronous,
            once,
            stack);
    }
    

    /**
     * Exécute une requête HTTP du type POST puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route interne de l'application.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {array} parameters Les paramètres supplémentaires au corps de la requête.
     * @param {number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchronous.
     * @param {boolean} once Si une seule requête doit être exécuté pour la même route.
     * @param {boolean} stack Si la requête doit attendre que la précédente se termine ou si elle est ignorée.
     * @returns {void}
     */
    static post(route, sucess = null, empty = null, failed = null, expired = null, parameters = {}, timeout = 0, asynchronous = true, once = false, stack = false) {
        Rest.#manage(
            Location.build(route),
            Http.METHOD_POST,
            false,
            route,
            sucess,
            null,
            null,
            empty,
            failed,
            expired,
            JSON.stringify(parameters), 
            timeout,
            asynchronous,
            once,
            stack);
    }
    

    /**
     * Exécute une requête HTTP du type PUT puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route interne de l'application.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {array} parameters Les paramètres supplémentaires au corps de la requête.
     * @param {number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchronous.
     * @param {boolean} once Si une seule requête doit être exécuté pour la même route.
     * @param {boolean} stack Si la requête doit attendre que la précédente se termine ou si elle est ignorée.
     * @returns {void}
     */
    static put(route, sucess = null, empty = null, failed = null, expired = null, parameters = {}, timeout = 0, asynchronous = true, once = false, stack = false) {
        Rest.#manage(
            Location.build(route),
            Http.METHOD_PUT,
            false,
            route,
            sucess,
            null,
            null,
            empty,
            failed,
            expired,
            JSON.stringify(parameters), 
            timeout,
            asynchronous,
            once,
            stack);
    }
    

    /**
     * Exécute une requête HTTP du type DELETE puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route interne de l'application.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {array} parameters Les paramètres supplémentaires au corps de la requête.
     * @param {number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchronous.
     * @param {boolean} once Si une seule requête doit être exécuté pour la même route.
     * @param {boolean} stack Si la requête doit attendre que la précédente se termine ou si elle est ignorée.
     * @returns {void}
     */
    static delete(route, sucess = null, empty = null, failed = null, expired = null, parameters = {}, timeout = 0, asynchronous = true, once = false, stack = false) {
        Rest.#manage(
            Location.build(route),
            Http.METHOD_DELETE,
            false,
            route,
            sucess,
            null,
            null,
            empty,
            failed,
            expired,
            JSON.stringify(parameters), 
            timeout,
            asynchronous,
            once,
            stack);
    }
    

    /**
     * Exécute une requête HTTP du type PATCH puis l'envoi à la fonction de succès.
     * 
     * @param {string} route La route interne de l'application.
     * @param {function} sucess Fonction anonyme appeler lors de la réponse.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {array} parameters Les paramètres supplémentaires au corps de la requête.
     * @param {number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchronous.
     * @param {boolean} once Si une seule requête doit être exécuté pour la même route.
     * @param {boolean} stack Si la requête doit attendre que la précédente se termine ou si elle est ignorée.
     * @returns {void}
     */
    static patch(route, sucess = null, empty = null, failed = null, expired = null, parameters = {}, timeout = 0, asynchronous = true, once = false, stack = false) {
        Rest.#manage(
            Location.build(route),
            Http.METHOD_PATCH,
            false,
            route,
            sucess,
            null,
            null,
            empty,
            failed,
            expired,
            JSON.stringify(parameters), 
            timeout,
            asynchronous,
            once,
            stack);
    }


    /**
     * Gère la pile d'appels.
     * 
     * @param {string} url L'URL de la requête.
     * @param {string} method La méthode de la requête.
     * @param {boolean} multiple Si on doit itérer sur la réponse.
     * @param {string} route La route interne de l'application.
     * @param {function} sucess Fonction anonyme appeler sur chaque réponse.
     * @param {function} before Fonction anonyme appeler avant l'itération.
     * @param {function} after Fonction anonyme appeler après l'itération.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {array} parameters Les paramètres supplémentaires à l'URL.
     * @param {number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchronous.
     * @param {boolean} once Si une seule requête doit être exécuté pour la même route.
     * @param {boolean} stack Si la requête doit attendre que la précédente se termine ou si elle est ignorée.
     * @returns {void}
     */
    static #manage(url, method, multiple, route, sucess, before, after, empty, failed, expired, parameters, timeout, asynchronous, once, stack) {
        if (once && Rest.#running(route)) {
            if (stack) {
                let id = setInterval(() => {
                    if (!Rest.#running(route)) {
                        clearInterval(id);
                        Rest.#send(url, method, multiple, route, sucess, before, after, empty, failed, expired, parameters, timeout, asynchronous);
                    }
                }, 100);
            }
        } else {
            Rest.#send(url, method, multiple, route, sucess, before, after, empty, failed, expired, parameters, timeout, asynchronous);
        }
    }


    /**
     * Exécute une requête HTTP puis gère sa réponse.
     * 
     * @param {string} url L'URL de la requête.
     * @param {string} method La méthode de la requête.
     * @param {boolean} multiple Si on doit itérer sur la réponse.
     * @param {string} route La route interne de l'application.
     * @param {function} sucess Fonction anonyme appeler sur chaque réponse.
     * @param {function} before Fonction anonyme appeler avant l'itération.
     * @param {function} after Fonction anonyme appeler après l'itération.
     * @param {function} empty Fonction anonyme appeler si aucun resultat retourné.
     * @param {function} failed Fonction anonyme appeler si la requête échoue.
     * @param {function} expired Fonction anonyme appeler si la requête expire.
     * @param {array} parameters Les paramètres supplémentaires à l'URL.
     * @param {number} timeout Le temps d'attente avant échec.
     * @param {boolean} asynchronous Si la requête doit s'exécuter en asynchronous.
     * @returns {void}
     */
    static #send(url, method, multiple, route, sucess, before, after, empty, failed, expired, parameters, timeout, asynchronous) {
        Rest.#begin(route);
        Http.send(
            url,
            'application/json; charset=utf-8',
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
                        let status = json.status;
                        let code = json.code;
                        if (status >= 200 && status < 300 && code === 0) {
                            let content = json.content;
                            if (content !== null && 
                                content !== undefined && 
                                content !== '') {
                                if (multiple) {
                                    if (Array.isArray(content) && content.length > 0) {
                                        if (before) before(json);
                                        if (sucess) content.forEach(element => sucess(element, json));
                                        if (after) after(json);
                                    } else {
                                        if (empty) empty(json);
                                    }
                                } else {
                                    if (!Array.isArray(content) || content.length > 0) {
                                        if (sucess) sucess(content, json);
                                    } else {
                                        if (empty) empty(json);
                                    }
                                }
                            } else {
                                if (empty) empty(json);
                            }
                        } else {
                            if (failed) failed(json);
                        }
                    } else {
                        if (failed) failed();
                    }
                } else {
                    if (failed) failed();
                }
                Rest.#end(route);
            },
            () => {
                if (failed) failed();
                Rest.#end(route);
            },
            () => {
                if (expired) expired();
                Rest.#end(route);
            },
            method,
            parameters,
            timeout,
            asynchronous
        );
    }


    /**
     * Ajoute une route à la pile d'appel.
     * 
     * @param {string} route La route interne de l'application.
     * @returns {void}
     */
    static #begin(route) {
        Rest.#loading.push(route);
    }


    /**
     * Enlève une route à la pile d'appel.
     * 
     * @param {string} route La route interne de l'application.
     * @returns {void}
     */
    static #end(route) {
        let index = Rest.#loading.indexOf(route);
        if (index > -1) {
            Rest.#loading.splice(index, 1);
        }
    }


    /**
     * Vérifi si une route de trouve dans la pile d'appel.
     * 
     * @param {string} route La route interne de l'application.
     * @returns {void}
     */
    static #running(route) {
        return Rest.#loading.includes(route);
    }

}