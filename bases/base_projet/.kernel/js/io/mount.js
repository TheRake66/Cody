import Finder from '../html/finder.js';
import Attribute from '../html/attribute.js';
import Dom from '../html/dom.js';



/**
 * Librairie gérant le montage des composants.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.1.0.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
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
	 * Monte un composant dans le script. Charge la balise principale du composant ainsi que tous les éléments du composant.
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

                let id = Attribute.get(child, 'id');
                if (id) {
                    this[id] = child;
                }
                
                let classs = Attribute.get(child, 'class');
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
     * Détruit le composant.
     * 
     * @return {void}
     */
    destroy() {
        let name = this.constructor.name;
        let components = window.components[name];
        if (components instanceof Array) {
            let component = components.find(component => component.uuid === this.uuid);
            if (component) {
                let index = components.indexOf(component);
                window.components[name].splice(index, 1);
                delete window.components[name][index];
            } else {
                throw new Error(`Impossible de détruire le composant "${name}" avec l'UUID "${this.uuid}".`);
            }
        } else {
            window.components[name] = null;
            delete window.components[name];
        }
        Dom.destroy(this.$);
    }


    /**
     * Enregistre un événement du composant.
     * 
     * @param {function} callback La fonction à exécuter lors de l'événement.
     * @param {string} event Le nom de l'événement.
     * @return {void}
     */
    register(callback, event = 'refresh') {
        let realevent = this.#realName(event);
        let openLog = this.#openLog;
        let copyThis = this;
        let realcallback = (event, ...args) => {

            openLog('✅ Exécution', realevent, [
                [ 'Événement', event ],
                [ 'Données', event.detail ]
            ], copyThis);

            callback(event, ...args);
        }

        this.#openLog('🛂 Enregistrement', realevent);

        this.events[realevent] = realcallback;
        this.$.addEventListener(realevent, realcallback);
    }


    /**
     * Supprime un événement du composant.
     * 
     * @param {string} event Le nom de l'événement.
     * @return {void}
     */
    unregister(event = 'refresh') {
        let realevent = this.#realName(event);

        this.#openLog('🚮 Suppression', realevent);

        this.$.removeEventListener(realevent, this.events[realevent]);
        delete this.events[realevent];
    }


    /**
     * Déclenche un événement du même composant.
     * 
     * @param {string} event Le nom de l'événement.
     * @param {any} data Les données à envoyer à l'événement.
     * @returns {void}
     */
    trigger(event = 'refresh', data = null) {
        let realevent = this.#realName(event);

        this.#openLog('🔃 Auto-déclenchement', realevent, [
            [ 'Données', data ]
        ]);

        this.$.dispatchEvent(new CustomEvent(realevent, { detail: data }));
    }


    /**
     * Déclenche un événement des composants parents.
     * 
     * @param {string} event Le nom de l'événement.
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} tag Un balise spécifique, seul les composants parent ayant cette balise seront déclencher.
     * @param {boolean} cascade Si l'événement doit être déclenché que pour le premier composant parent ou tous jusqu'au premier composant de la page.
     * @return {void}
     */
    emit(event = 'refresh', data = null, tag = null, cascade = false) {
        let parent = this.$;
        let realevent = this.#realName(event);

        this.#openLog('🔼 Déclenchement montant', realevent, [
            [ 'Données', data ],
            [ 'Balise', tag ],
            [ 'Cascade', cascade ]
        ]);

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
     * @param {boolean} cascade Si l'événement doit être déclenché que pour les premiers composants enfants ou tous jusqu'aux derniers composants de la page.
     * @param {number} start Le nombre de composants à ignorer avant de commencer à déclencher les événements.
     * @param {number} offset Le nombre de composants à déclencher après le premier composant trouvé.
     * @return {void}
     */
    pass(event = 'refresh', data = null, tag = null, cascade = false, start = null, offset = null) {
        let childrens = Finder.queryAll(cascade ?
            'component' : 
            'component:not(:scope > * component component)', this.$);
        let realevent = this.#realName(event);

        this.#openLog('🔽 Déclenchement descendant', realevent, [
            [ 'Données', data ],
            [ 'Balise', tag ],
            [ 'Cascade', cascade ],
            [ 'Début', start ],
            [ 'Limite', offset ]
        ]);

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
     * @param {boolean} cascade Si l'événement doit être déclenché que pour les premiers composants enfants ou tous jusqu'aux derniers composants de la page.
     * @param {number} start Le nombre de composants à ignorer avant de commencer à déclencher les événements.
     * @param {number} offset Le nombre de composants à déclencher après le premier composant trouvé.
     * @param {boolean} rising Si l'événement doit être déclenché d'avord sur les composants enfants ou sur les composants parents.
     * @return {void}
     */
    spread(event = 'refresh', data = null, tag = null, cascade = false, start = null, offset = null, rising = true) {
        let realevent = this.#realName(event);
        
        this.#openLog('🔀 Déclenchement descendant et montant', realevent, [
            [ 'Données', data ],
            [ 'Balise', tag ],
            [ 'Cascade', cascade ],
            [ 'Début', start ],
            [ 'Limite', offset ],
            [ 'Enfant d\'abord', rising ]
        ]);
        
        if (rising) {
            this.pass(event, data, tag, cascade, start, offset);
            this.emit(event, data, tag, cascade);
        } else {
            this.emit(event, data, tag, cascade);
            this.pass(event, data, tag, cascade, start, offset);
        }
    }


    /**
     * Enregistre un événement, déclenche le même événement pour des composants enfant, attends la ou les réponses, exécute la fonction, supprime l'événement.
     * 
     * @param {function} callback La fonction à exécuter lors de l'événement.
     * @param {string} event Le nom de l'événement.
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} tag Un balise spécifique, seul les composants enfant ayant cette balise seront déclencher.
     * @param {boolean} cascade Si l'événement doit être déclenché que pour les premiers composants enfants ou tous jusqu'aux derniers composants de la page.
     * @param {number} start Le nombre de composants à ignorer avant de commencer à déclencher les événements.
     * @param {number} offset Le nombre de composants à déclencher après le premier composant trouvé.
     * @param {number} count Le nombre de données à recevoir, équivalent au nombre de composant enfant devant répondre.
     * @return {void}
     */
    toogle(callback, event = 'get', data = null, tag = null, cascade = false, start = null, offset = null, count = 1) {
        let realevent = this.#realName(event);
        let retrieve = [];
        let openLog = this.#openLog;
        let copyThis = this;

        this.#openLog('🎦 Analyse des données', realevent, [
            [ 'Données', data ],
            [ 'Balise', tag ],
            [ 'Cascade', cascade ],
            [ 'Début', start ],
            [ 'Limite', offset ],
            [ 'Nombre', count ]
        ]);

        this.register(e => {

            let numero = (retrieve.length + 1).toString()
                .replace('0', '0️⃣')
                .replace('1', '1️⃣')
                .replace('2', '2️⃣')
                .replace('3', '3️⃣')
                .replace('4', '4️⃣')
                .replace('5', '5️⃣')
                .replace('6', '6️⃣')
                .replace('7', '7️⃣')
                .replace('8', '8️⃣')
                .replace('9', '9️⃣');

            openLog(`${numero} Réception d\'une donnée`, realevent, [
                [ 'Numéro', retrieve.length ],
                [ 'Événement', event ],
                [ 'Données', e.detail ]
            ], copyThis);

            if (count === 1) {
                callback(e);
                this.unregister(event);
            } else {
                retrieve.push(e);
                if (retrieve.length === count) {
                    callback(retrieve);
                    this.unregister(event);
                }
            }
        }, event);
        
        this.pass(event, data, tag, cascade, start, offset);
    }


	/**
	 * Enregistre un événement, puis lors de son appel, déclenche le même événement 
	 * pour son composant parent avec les données renvoyées par la fonction de callback.
	 * 
	 * @param {function} callback La fonction à exécuter lors de l'événement.
	 * @param {string} event Le nom de l'événement.
	 * @param {string} tag Un balise spécifique, seul les composants parent ayant cette balise seront déclencher.
	 * @param {boolean} cascade Si l'événement doit être déclenché que pour le premier composant parent ou tous jusqu'au premier composant de la page.
	 * @return {void}
	 */
	getter(callback, event = 'get', tag = null, cascade = false) {
        let realevent = this.#realName(event);

        this.#openLog('🔂 Préparation de l\'accès à la donnée', realevent, [
			[ 'Balise', tag ],
			[ 'Cascade', cascade ]
        ]);

		this.register(e => {
			let data = callback(e);

			this.#openLog('🔁 Réception de la donnée', realevent, [
				[ 'Données', data ]
			]);

			this.emit(event, data, tag, cascade);
		}, event);
	}


    /**
     * Enregistre un événement, puis lors de son appel, déclenche une fonction de callback avec les données reçues.
     * 
     * @param {function} callback La fonction à exécuter lors de l'événement.
     * @param {string} event Le nom de l'événement.
     * @return {void}
     */
    setter(callback, event = 'set') {
        let realevent = this.#realName(event);

        this.#openLog('🔂 Préparation de la modification de la donnée', realevent);

        this.register(e => {
            this.#openLog('🔁 Modification de la donnée', realevent);

            callback(e);
        }, event);
    }


    /**
     * Déclenche un événement des composants enfants lors d'un déclenchement d'un événement par un autre enfant.
     * 
     * @param {string} event Le nom de l'événement.
     * @param {any} data Les données à envoyer à l'événement.
     * @param {string} tag Un balise spécifique, seul les composants enfant ayant cette balise seront déclencher.
     * @param {boolean} cascade Si l'événement doit être déclenché que pour les premiers composants enfants ou tous jusqu'aux derniers composants de la page.
     * @param {number} start Le nombre de composants à ignorer avant de commencer à déclencher les événements.
     * @param {number} offset Le nombre de composants à déclencher après le premier composant trouvé.
     * @return {void}
     */
    mirror(event = 'submit', tag = null, cascade = false, start = null, offset = null) {
        let realevent = this.#realName(event);

        this.#openLog('🔂 Préparation au renvoi de la donnée', realevent);

        this.register(e => {
            this.#openLog('🔁 Renvoi de la donnée', realevent);

			let data = e.detail;  

            this.pass(event, data, tag, cascade, start, offset);
        }, event);

    }
    
 
    /**
     * Préfixe le nom de l'événement avec afin de ne pas interférer avec les événements natifs.
     * 
     * @param {string} event Le nom de l'événement.
     * @returns {string} Le nom de l'événement avec le préfixe.
     */
    #realName(event) {
        return `cody://${event}`;
    }


    /**
     * Ouvre un groupe de log.
     * 
     * @param {string} label Le message à afficher.
     * @param {string} event Le nom de l'événement.
     * @param {array} logs Les données à afficher.
     * @param {object} instance L'instance du composant.
     * @return {void}
     */
    #openLog(label, event, logs = [], instance = this) {
        console.groupCollapsed(`${label} : ${event} 🠊 ${instance.constructor.name}[${instance.uuid}]`);
            console.groupCollapsed('Composant');
            console.log('UUID :', instance.uuid);
            console.log('Nom :', instance.constructor.name);
            console.log('Élément :', instance.$);
            console.log('Référence :', instance);
            console.groupEnd();
        logs.forEach(log => {
            console.log(`${log[0]} :`, log[1]);
        });
        console.groupEnd();
    }
    
}