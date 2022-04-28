/**
 * Librairie de gestion du DOM
 */
export default class Html {
    
    
    /**
     * Retourne un élément HTML via son ID
     * 
     * @param {string} id l'id de l'élément
     * @returns {HTMLElement} l'élément
     */
    static id(id) {
        return document.getElementById(id);
    }


    /**
     * Retourne des éléments HTML via leur tag
     * 
     * @param {string} id l'id de l'élément
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {HTMLCollectionOf<any>} l'élément
     */
    static tag(tag, parent = document.body) {
        return parent.getElementsByTagName(tag);
    }


    /**
     * Retourne un élément HTML depuis un selecteur CSS
     * 
     * @param {string} selector le sélecteur css
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {HTMLElement} l'élément
     */
    static query(selector, parent = document.body) {
        return parent.querySelector(selector);
    }
    

    /**
     * Retourne des éléments HTML depuis leur selecteur CSS
     * 
     * @param {string} selector le sélecteur css
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {NodeListOf<Element>} les éléments
     */
    static queryAll(selector, parent = document.body) {
        return parent.querySelectorAll(selector);
    }


    /**
     * Vide le contenu d'un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @returns {void}
     */
    static clear(el = document.body) { 
        el.innerHTML = '';
    }
    

    /**
     * Detruit un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @returns {void}
     */
    static destroy(el = document.body) {
        el.remove();
    }


    /**
     * Crée un élément HTML
     * 
     * @param {string} tag le tag de l'élément
     * @param {Object} attr les attributs de l'élément
     * @param {string} content le contenu HTML de l'élément
     * @returns {HTMLElement} l'élément créé
     */
    static create(tag, attr = null, content = null) {
        let el = document.createElement(tag);
        if (attr) {
            for (let key in attr) {
                el.setAttribute(key, attr[key]);
            }
        }
        if (content) {
            el.innerHTML = content;
        }
        return el;
    }


    /**
     * Insert du code HTML dans un élément HTML a une position donnée
     * 
     * @param {string} html le contenu HTML
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @param {string} position l'endroit où insérer le contenu HTML par défaut (beforeend)
     * @returns {void}
     */
    static insert(html, el = document.body, position = 'beforeend') {
        el.insertAdjacentHTML(position, html);
    }


    /**
     * Vide le contenu d'un élément HTML puis insert du code HTML dedans
     * 
     * @param {HTMLElement} html le contenu HTML
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @returns {void}
     */
    static replace(html, el) {
        el.innerHTML = html;        
    }


    /**
     * Insert un élément HTML dans le DOM
     * 
     * @param {HTMLElement} el l'élément HTML à insérer
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {void}
     */
    static append(el, parent = document.body) {
        parent.appendChild(el);
    }


    /**
     * Supprime un élément HTML dan le DOM
     * 
     * @param {HTMLElement} el l'élément HTML à supprimer
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     */
    static remove(el, parent = document.body) {
        parent.removeChild(el);
    }


    /**
     * Retourne les coordonnées d'une cellule d'un tableau
     * 
     * @param {HTMLElement} el la cellule (ou un de ses enfants)
     * @returns {Object} les coordonnées de la cellule (x, y)
     */
    static cellCoor(el) {
        let coor = {
            x: 0,
            y: 0
        };
        while (el.tagName !== 'TD') {
            el = el.parentElement;
        }
        coor.x = el.cellIndex;
        coor.y = el.parentElement.rowIndex;
        return coor;
    }
    

    /**
     * Ajoute une classe à un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @param {string} className la classe à ajouter
     * @returns {void}
     */
    static addClass(el, className) {
        el.classList.add(className);
    }


    /**
     * Supprime une classe à un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @param {string} className la classe à supprimer
     * @returns {void}
     */
    static removeClass(el, className) {
        el.classList.remove(className);
    }


    /**
     * Verifie si une classe est présente sur un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @param {string} className la classe à ajouter
     * @returns {boolean} true si la classe est présente, false sinon
     */
    static hasClass(el, className) {
        return el.classList.contains(className);
    }


    /**
     * Ajoute une classe à un élément HTML si elle n'est pas présente, sinon la supprime
     * 
     * @param {HTMLElement} el l'élément HTML
     * @param {string} className la classe à ajouter
     * @returns {void}
     */
    static toggleClass(el, className) {
        el.classList.toggle(className);
    }

    
    /**
     * Ajoute un écouteur d'événement à un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @param {string} event le nom de l'événement
     * @param {function} callback la fonction à exécuter
     * @param {boolean} capture si repercute l'événement sur les éléments enfants
     * @param {boolean} once si l'événement doit être écouté une seule fois
     * @param {boolean} passive si la fonction peut appeler la méthode preventDefault()
     * @returns {void}
     */
    static addEvent(el, event, callback, capture = false, once = false, passive = false) {
        el.addEventListener(event, callback, {
            capture: capture,
            once: once,
            passive: passive
        });
    }


    /**
     * Desactive un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static disabled(el) {
        el.disabled = true;
    }


    /**
     * Active un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     * @returns {void}
     */
    static enabled(el) {
        el.disabled = false;
    }


    /**
     * Passe un élément HTML en mode lecture seule
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static readonly(el) {
        el.readOnly = true;
    }


    /**
     * Passe un élément HTML en mode écriture
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static writable(el) {
        el.readOnly = false;
    }


    /**
     * Cache un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static hide(el) {
        el.style.display = 'none';
    }


    /**
     * Affiche un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static show(el) {
        el.style.display = 'unset';
    }

}