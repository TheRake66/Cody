import Http from './http.js';



/**
 * Librairie de communication avec l'API REST en PHP
 */
export default class Rest {

    /**
     * Execute une requete REST puis retourne le resultat
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} callback fonction anonyme appeler lors de la reponse
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec de Ajax
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {string} method la methode d'envoi
     */
    static ask(route, rest, callback = null, empty = null, fail = null, param = {}, method = Http.METHOD_GET) {
        let _ = {};
        _['r'] = route;
        _[rest] = true;
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
     * Execute une requete REST puis boucle sur les resultats
     * 
     * @param {string} route la route
     * @param {string} rest le nom de la fonction cote API
     * @param {function} callback fonction anonyme appeler sur chaque reponse
     * @param {function} pre fonction anonyme appeler avant l'iteration
     * @param {function} post fonction anonyme appeler apres l'iteration
     * @param {function} empty fonction anonyme appeler si resultat vide
     * @param {function} fail fonction anonyme appeler si echec de Ajax
     * @param {Array} param les parametres supplementaires a l'URL
     * @param {string} method la methode d'envoi
     */
    static askFor(route, rest, callback = null, pre = null, post = null, empty = null, fail = null, param = {}, method = Http.METHOD_GET) {
        let _ = {};
        _['r'] = route;
        _[rest] = true;
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