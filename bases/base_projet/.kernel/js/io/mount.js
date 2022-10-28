import Dom from '../html/dom.js';
import Builder from '../html/builder.js';
import Finder from '../html/finder.js';
import Thread from '../io/thread.js';



/**
 * Librairie gérant le montage des composants.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Mount {

    /**
     * @var {string} uuid L'identifiant unique du composant.
     */
    uuid = null;

    /**
     * @var {HTMLElement} $ L'élément du composant.
     */
    $ = null;

    /**
     * @var {Object} events La liste des événements enregistrés.
     */
    events = {};


    /**
	 * Monte un composant dans le script. Charge la balise principale du composant
     * ainsi que tous les éléments du composant.
	 * 
     * @access public
     * @param {string} uuid L'identifiant unique du composant.
     * @return {void}
     */
    constructor(uuid) {
        this.uuid = uuid;
        let components = Finder.queryAll(`component[data-uuid="${uuid}"]`);
        if (components.length === 1) {
            this.$ = components[0];
            this.$.style.display = 'inherit';

            let childrens = Finder.queryAll(`* :not(
                component[data-uuid="${uuid}"] component, 
                component[data-uuid="${uuid}"] component *)`, this.$);
            for (let i = 0; i < childrens.length; i++) {
                let child = childrens[i];

                let id = child.getAttribute('id');
                if (id) {
                    this[id] = child;
                }
                
                let classs = child.getAttribute('class');
                if (classs) {
                    let split = classs.split(' ');
                    for (let j = 0; j < split.length; j++) {
                        if (!this[split[j]]) {
                            this[split[j]] = [];
                        }
                        this[split[j]].push(child);
                    }
                }
            }
        } else if (components.length > 1) {
            throw new Error(`Impossible de monter le composant "${uuid}". Plusieurs composants ont le même UUID.`);
        } else {
            throw new Error(`Impossible de monter le composant "${uuid}". Aucun composant ne correspond à cet UUID.`);
        }
    }


    /**
     * Déclenche un événement des composants parents.
     * 
     * @param {string} event Le nom de l'événement.
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} tag Un balise spécifique, seul les composants parent ayant cette balise seront déclencher.
     * @param {bool} cascade Si l'événement doit être déclenché que pour 
     * le premier composant parent ou tous jusqu'au premier composant de la page.
     * @return {void}
     */
    emit(event = 'refresh', data = null, tag = null, cascade = false) {
        let parent = this.$;
        let realevent = this.#realname(event);

        do {
            parent = parent.parentElement.closest('component');
            if (parent && (tag === null || Finder.queryAll(`:scope > ${tag}` , parent).length > 0)) {
                parent.dispatchEvent(new CustomEvent(realevent, {
                    detail: data
                }));
            }
        } while (parent && cascade);
    }


    /**
     * Déclenche un événement des composants enfants.
     * 
     * @param {string} event Le nom de l'événement.
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} tag Un balise spécifique, seul les composants enfant ayant cette balise seront déclencher.
     * @param {bool} cascade Si l'événement doit être déclenché que pour 
     * les premiers composants enfants ou tous jusqu'aux derniers composants de la page.
     * @param {number} start Le nombre de composants à ignorer avant de commencer à déclencher les événements.
     * @param {number} offset Le nombre de composants à déclencher après le premier composant trouvé.
     * @return {void}
     */
    pass(event = 'refresh', data = null, tag = null, cascade = false, start = null, offset = null) {
        let childrens = Finder.queryAll(cascade ?
            'component' : 
            'component:not(:scope > * component component)', this.$);
        let realevent = this.#realname(event);

        let count = 0;
        for (let i = 0; i < childrens.length; i++) {
            const child = childrens[i];

            if (tag === null || Finder.queryAll(`:scope > ${tag}`, child).length > 0) {
                if (start === null || count >= start) {
                    child.dispatchEvent(new CustomEvent(realevent, {
                        detail: data
                    }));
                }
                count++;
                if (offset !== null && count >= start + offset) {
                    break; 
                }
            }
        }
    }


    /**
     * Déclenche un événement des composants enfants et parents.
     * 
     * @param {string} event Le nom de l'événement.
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} tag Un balise spécifique, seul les composants enfant ayant cette balise seront déclencher.
     * @param {bool} cascade Si l'événement doit être déclenché que pour 
     * les premiers composants enfants ou tous jusqu'aux derniers composants de la page.
     * @param {number} start Le nombre de composants à ignorer avant de commencer à déclencher les événements.
     * @param {number} offset Le nombre de composants à déclencher après le premier composant trouvé.
     * @param {bool} childFirst Si l'événement doit être déclenché d'avord sur les composants enfants ou sur les composants parents.
     * @return {void}
     */
    spread(event = 'refresh', data = null, tag = null, cascade = false, start = null, offset = null, childFirst = true) {
        let realevent = this.#realname(event);

        if (childFirst) {
            this.pass(realevent, data, tag, cascade, start, offset);
            this.emit(realevent, data, tag, cascade);
        } else {
            this.emit(realevent, data, tag, cascade);
            this.pass(realevent, data, tag, cascade, start, offset);
        }
    }


    /**
     * Enregistre un événement du composant.
     * 
     * @param {function} callback La fonction à exécuter lors de l'événement.
     * @param {string} event Le nom de l'événement.
     * @return {void}
     */
    register(callback, event = 'refresh') {
        let realevent = this.#realname(event);
        this.events[realevent] = callback;
        this.$.addEventListener(realevent, callback);
    }


    /**
     * Supprime un événement du composant.
     * 
     * @param {string} event Le nom de l'événement.
     * @return {void}
     */
    unregister(event = 'refresh') {
        let realevent = this.#realname(event);
        this.$.removeEventListener(realevent, this.events[realevent]);
        delete this.events[realevent];
    }


    /**
     * Enregistre un événement, déclenche le même événement pour des composants enfant,
     * attends la ou les réponses, exécute la fonction, supprime l'événement.
     * 
     * @param {function} callback La fonction à exécuter lors de l'événement.
     * @param {string} event Le nom de l'événement.
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} tag Un balise spécifique, seul les composants enfant ayant cette balise seront déclencher.
     * @param {bool} cascade Si l'événement doit être déclenché que pour 
     * les premiers composants enfants ou tous jusqu'aux derniers composants de la page.
     * @param {number} count Le nombre de données à recevoir, équivalent au nombre de composant enfant
     * devant répondre.
     * @return {void}
     */
    toogle(callback, event = 'get', data = null, tag = null, cascade = false, count = 1) {
        let realevent = this.#realname(event);

        let retrieve = [];
        this.register(e => {
            if (count === 1) {
                callback(e.detail);
                this.unregister(realevent);
            } else {
                retrieve.push(e.detail);
                if (retrieve.length === count) {
                    callback(retrieve);
                    this.unregister(realevent);
                }
            }
        }, realevent);
        this.pass(realevent, data, tag, cascade);
    }
 
 
    /**
     * Préfixe le nom de l'événement avec afin de ne pas interférer 
     * avec les événements natifs.
     * 
     * @param {string} event Le nom de l'événement.
     * @returns {string} Le nom de l'événement avec le préfixe.
     */
    #realname(event) {
        return `cody:://${event}`;
    }
    
}