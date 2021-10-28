/**
 * @constructor
 */
 var Accueil = function() {

    this.cmd = document.getElementById('input');

    this.loadEvent = () => {
        document.getElementById('cd').onclick = () => this.setConsoleText('cd C:\\wamp\\www');
        document.getElementById('ls').onclick = () => this.setConsoleText('ls');
        document.getElementById('new').onclick = () => this.setConsoleText('new mon-projet');
        document.getElementById('com').onclick = () => this.setConsoleText('com -a mon-composant');
        document.getElementById('obj').onclick = () => this.setConsoleText('obj -a mon-objet');
    }

    
    this.setConsoleText = async (command) => {
        while (this.cmd.innerText != '') {
            this.cmd.textContent = this.cmd.innerHTML.slice(0, -1); 
            await this.wait(20);
        }
        for (let i = 0; i < command.length; i++) {
            this.cmd.textContent += command.substring(i, i+1); 
            await this.wait(20);
        }
    }

    this.loopCursor = async () => {
        while (true) {
            this.cmd.textContent += '█'; 
            await this.wait(500);
            this.cmd.textContent = this.cmd.innerHTML.replace('█', ''); 
            await this.wait(500);
        }
    }
    
    this.wait = (ms) => {
        return new Promise(res => setTimeout(res, ms));
    }

};


Accueil = new Accueil();

Accueil.loadEvent();
Accueil.loopCursor();