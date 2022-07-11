import Dom from '../html/dom.js';
import Builder from '../html/builder.js';
import Finder from '../html/finder.js';



/**
 * Librairie de gestion des sons.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Sound {

    /**
	 * Lis un fichier audio et le joue.
	 * 
     * @param {string|Audio} track Le chemin du fichier audio.
     * @param {int} volume Le volume du son.
     * @param {boolean} loop Si le son doit être joué en boucle.
     * @param {int} timeout Le temps avant chaque tentative de lecture du son.
     * @return {void}
     */
     static async play(track, volume = 1, loop = false, onSuccess = null, onError = null, timeout = 500) {
        if (typeof track === 'string') {
            track = new Audio(track);
        }
        track.volume = volume;
        if (loop) {
            track.addEventListener('ended', function() {
                this.currentTime = 0;
                this.play();
            }, false);
        }
        let played = false;
        while (!played) {
            let response = track.play();
            response
                .then(_ => {
                    played = true;
                    if (onSuccess) {
                        onSuccess();
                    }
                })
                .catch(e => {
                    if (onError) {
                        onError(e);
                    }
                });
            await new Promise(r => setTimeout(r, timeout));
        }
    }

}