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
     * @param {string} tag La balise du composant.
     * @return {void}
     */
    mount(tag) {
        let components = Finder.tag(tag);
        this.component = components[components.length - 1];
        if (this.component) {

            let childrens = Finder.queryAll('*', this.component);
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
        }
    }

}