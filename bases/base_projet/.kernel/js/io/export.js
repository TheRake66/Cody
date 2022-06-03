import Dom from '../html/dom.js';
import Builder from '../html/builder.js';
import Finder from '../html/finder.js';



/**
 * Librairie d'exportation des donnees
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Export {

    /**
	 * Telecharge un contenu
	 * 
	 * @param {any} content le contenu a telecharger
	 * @param {string} file nom du fichier
     * @returns {void}
     */
    static download(content, file = 'download.txt') {
        let a = Builder.create('a', {
            href: 'data:text/plain;charset=utf-8,' + encodeURIComponent(content),
            download: file,
            style: 'display:none'
        });
        DOM.append(a);
        a.click();
        DOM.remove(a);
    }
    

    /**
     * Affiche du texte dans un nouvel onglet
     * 
     * @param {string} content le contenu de la page
     * @returns {void}
     */
    static fullscreen(content) {
        let tab = window.open('about:blank', '_blank');
        tab.document.write('<pre>' + content + '</pre>');
        tab.document.close();
    }


    /**
	 * Convertit un tableau en CSV
	 * 
	 * @param {DOMElement} table le tableau
	 * @param {string} spearator separateur de colonne
	 * @param {string} arround caractere autour de chaque cellules
     * @returns {void}
     */
     static csv(table, spearator = ';', arround = '"') {
        let csv = '';

        // Creer le header
        let heads = Finder.queryAll('thead th', table);
        heads.forEach(cell => {
            csv += arround + cell.innerText + arround + spearator;
        });
        csv = csv.slice(0, -1) + '\n';

        // Creer les lignes
        let rows = Finder.queryAll('tbody tr', table);
        rows.forEach(row => {
            if (row.style.display != 'none') {
                let cells = Finder.queryAll('td', row);
                cells.forEach(cell => {
                    csv += arround + cell.innerText.replace(/\n/g, ' ') + arround + spearator;
                });
                csv = csv.slice(0, -1) + '\n';
            }
        });
        
        return csv;
    }

}