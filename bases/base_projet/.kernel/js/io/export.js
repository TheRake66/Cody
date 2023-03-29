import Dom from '../html/dom.js';
import Builder from '../html/builder.js';
import Finder from '../html/finder.js';



/**
 * Librairie d'exportation des données.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
export default class Export {

    /**
	 * Télécharge un contenu.
	 * 
	 * @param {any} content Le contenu à télécharger.
	 * @param {string} file Le nom du fichier.
     * @returns {void}
     */
    static download(content, file = 'download.txt', type = 'text/plain', charset = 'utf-8') {
        let a = Builder.create('a', {
            href: `data:${type};charset=${charset},${encodeURIComponent(content)}`,
            download: file,
            style: 'display: none;'
        });
        Dom.append(a);
        a.click();
        Dom.remove(a);
    }
    

    /**
     * Affiche du texte dans un nouvel onglet.
     * 
     * @param {string} content Le contenu de la page.
     * @returns {void}
     */
    static newtab(content) {
        let tab = window.open('about:blank', '_blank');
        tab.document.write(/*html*/`<pre>${content}</pre>`);
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

        Finder.queryAll('thead th', table).forEach(cell => {
            csv += arround + cell.innerText + arround + spearator;
        });
        csv = csv.slice(0, -1) + '\n';

        Finder.queryAll('tbody tr', table).forEach(row => {
            if (row.style.display !== 'none') {
                Finder.queryAll('td', row).forEach(cell => {
                    csv += arround + cell.innerText.replace(/\n/g, ' ') + arround + spearator;
                });
                csv = csv.slice(0, -1) + '\n';
            }
        });
        
        return csv;
    }

}