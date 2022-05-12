import Html from './html.js';



/**
 * Librairie d'exportation des donnees
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Librairie
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
        let a = Html.create('a', {
            href: 'data:text/plain;charset=utf-8,' + encodeURIComponent(content),
            download: file,
            style: 'display:none'
        });
        Html.append(a);
        a.click();
        Html.remove(a);
    }
    

    /**
     * Affiche du texte dans un nouvel onglet
     * 
     * @param {string} content le contenu de la page
     * @returns {void}
     */
    static fullScreen(content) {
        let tab = window.open('about:blank', '_blank');
        tab.document.write('<pre>' + content + '</pre>');
        tab.document.close();
    }

}