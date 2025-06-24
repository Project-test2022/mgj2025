import bgm from '../audio/negative/beautiful-ruin.mp3';
import { initAudio } from './bgm.js';

document.addEventListener('DOMContentLoaded', init);

function init() {
    initAudio(bgm);
}
