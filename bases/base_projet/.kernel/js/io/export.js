import Dom from '../html/dom.js';
import Builder from '../html/builder.js';
import Finder from '../html/finder.js';



/**
 * Librairie d'exportation des données.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Export {

    /**
	 * Télécharge un contenu.
	 * 
	 * @param {any} content le contenu à télécharger.
	 * @param {string} file le nom du fichier.
     * @returns {void}
     */
    static download(content, file = 'download.txt') {
        let a = Builder.create('a', {
            href: 'data:text/plain;charset=utf-8,' + encodeURIComponent(content),
            download: file,
            style: 'display:none'
        });
        Dom.append(a);
        a.click();
        Dom.remove(a);
    }
    

    /**
     * Affiche du texte dans un nouvel onglet.
     * 
     * @param {string} content le contenu de la page.
     * @returns {void}
     */
    static fullscreen(content) {
        let tab = window.open('about:blank', '_blank');
        tab.document.write('<pre>' + content + '</pre>');
        tab.document.close();
    }


    /**
	 * Convertit un tableau en CSV.
	 * 
	 * @param {DOMElement} table L'élément table à convertir.
	 * @param {string} spearator Le séparateur.
	 * @param {string} arround Le caractère autour de chaque cellule.
     * @returns {void}
     */
     static csv(table, spearator = ';', arround = '"') {
        let csv = '';

        let heads = Finder.queryAll('thead th', table);
        heads.forEach(cell => {
            csv += arround + cell.innerText + arround + spearator;
        });
        csv = csv.slice(0, -1) + '\n';

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