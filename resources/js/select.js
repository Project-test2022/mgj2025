import bgm from '../audio/op/high-stakes-shadow.mp3';
import { initAudio } from './bgm.js';

document.addEventListener('DOMContentLoaded', init);

function init() {
    initAudio(bgm);
}
