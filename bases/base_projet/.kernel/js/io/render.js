import Dom from '../html/dom.js';
import Builder from '../html/builder.js';
import Finder from '../html/finder.js';
import Thread from '../io/thread.js';



/**
 * Librairie gérant le rendu des composants.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Render {

    /**
	 * Monte un composant dans le script. Charge la balise principale du composant
     * ainsi que tous les éléments du composant.
	 * 
     * @access public
     * @param {string} uuid Le uuid du composant.
     * @return {void}
     */
    constructor(uuid) {
        let components = Finder.queryAll(`component[data-uuid="${uuid}"]`);
        if (components.length === 1) {
            this.$ = components[0];
            this.$.style.display = 'inherit';

            let childrens = Finder.queryAll('*', this.$);
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
     * Déclenche un événement du composant parent.
     * 
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} event Le nom de l'événement.
     * @param {bool} capture Si l'événement doit être déclenché une que pour 
     * le premier composant parent ou tous jusqu'au dernier composant de la page.
     * @return {void}
     */
    emit(data, event = 'onrefresh', capture = true) {
        let parent = this.$;
        do {
            parent = parent.parentElement.closest('component');
            if (parent) {
                parent.dispatchEvent(new CustomEvent(event, {
                    detail: data
                }));
            }
        } while (parent && !capture);
    }


    /**
     * Déclenche un événement du composant enfant.
     * 
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} event Le nom de l'événement.
     * @param {bool} capture Si l'événement doit être déclenché une que pour 
     * le premier composant parent ou tous jusqu'au premier composant de la page.
     * @return {void}
     */
    pass(data, event = 'onrefresh', capture = true) {
        let child = this.$;
        do {
            child = Finder.query('component', child);
            if (child) {
                child.dispatchEvent(new CustomEvent(event, {
                    detail: data
                }));
            }
        } while (child && !capture);
    }


    /**
     * Enregistre un événement du composant.
     * 
     * @param {function} callback La fonction à exécuter lors de l'événement.
     * @param {string} event Le nom de l'événement.
     * @return {void}
     */
    register(callback, event = 'onrefresh') {
        this.$.addEventListener(event, callback);
    }


    /**
     * Supprime un événement du composant.
     * 
     * @param {string} event Le nom de l'événement.
     * @return {void}
     */
    unregister(event = 'onrefresh') {
        this.$.removeEventListener(event);
    }
}