import successBgm from '../audio/positive/breakthrough-moment_v2.mp3';
import failBgm from '../audio/negative/bleeding-my-heart_v2.mp3';

import { initAudio } from './bgm.js';

document.addEventListener('DOMContentLoaded', init);

function init() {
    const result = document.getElementById('result').value;
    let bgm;
    if (result === 'true') {
        bgm = successBgm;
    } else {
        bgm = failBgm;
    }
    initAudio(bgm);
}
