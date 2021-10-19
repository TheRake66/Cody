// ####################################################################################################
var cmd = document.getElementById('input');



function loadEvent() {
    document.getElementById('cd').onclick = () => setConsoleText('cd C:\\wamp\\www');
    document.getElementById('ls').onclick = () => setConsoleText('ls');
    document.getElementById('new').onclick = () => setConsoleText('new mon-projet');
    document.getElementById('com').onclick = () => setConsoleText('com mon-projet -a mon-composant');
    document.getElementById('obj').onclick = () => setConsoleText('obj mon-projet -a mon-objet');
}



async function setConsoleText(command) {
    while (cmd.innerText != '') {
        cmd.textContent = cmd.innerHTML.slice(0, -1); 
        await wait(20);
    }
    for (let i = 0; i < command.length; i++) {
        cmd.textContent += command.substring(i, i+1); 
        await wait(20);
    }
}



async function loopCursor() {
    while (true) {
        cmd.textContent += '█'; 
        await wait(500);
        cmd.textContent = cmd.innerHTML.replace('█', ''); 
        await wait(500);
    }
}



function wait(ms) {
    return new Promise(res => setTimeout(res, ms));
}



loadEvent();
loopCursor();
// ####################################################################################################