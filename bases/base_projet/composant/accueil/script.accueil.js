
class Accueil {

    /**
     * Constructeur
     */
	constructor() {
		this.loadEvent();
		this.loopCursor();
	}
	

    /**
     * Array contenant les marker
     */
    cmd = document.getElementById('input');


    /**
     * Charge les event de click sur les boutons
     */
    loadEvent() {
        document.getElementById('cd').onclick = () => this.setConsoleText('cd C:\\wamp\\www');
        document.getElementById('ls').onclick = () => this.setConsoleText('ls');
        document.getElementById('new').onclick = () => this.setConsoleText('new mon-projet');
        document.getElementById('com').onclick = () => this.setConsoleText('com -a mon-composant');
        document.getElementById('obj').onclick = () => this.setConsoleText('obj -a mon-objet');
    }


    /**
     * Charge les event de click sur les boutons
     * 
     * @param {string} command - Commande a afficher
     */
    async setConsoleText (command){
        while (this.cmd.innerText != '') {
            this.cmd.textContent = this.cmd.innerHTML.slice(0, -1); 
            await this.wait(20);
        }
        for (let i = 0; i < command.length; i++) {
            this.cmd.textContent += command.substring(i, i+1); 
            await this.wait(20);
        }
    }


    /**
     * Boucle affichant le caractere du curseur
     */
    async loopCursor() {
        while (true) {
            this.cmd.textContent += '█'; 
            await this.wait(500);
            this.cmd.textContent = this.cmd.innerHTML.replace('█', ''); 
            await this.wait(500);
        }
    }


    /**
     * Met une pause dans le thread
     * 
     * @param {int} ms - Nombre de milliseconde
     */
    wait (ms) {
        return new Promise(res => setTimeout(res, ms));
    }

};

let a = new Accueil();