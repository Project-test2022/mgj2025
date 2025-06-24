import bgm from '../audio/op/high-stakes-shadow.mp3';
import { initAudio } from './bgm.js';

document.addEventListener('DOMContentLoaded', init);

function init() {
    initProfileToggles();
    initAudio(bgm);
}

/**
 * プロフィールと特殊能力の切り替え
 */
function initProfileToggles() {
    const btn = document.getElementById('toggleBtn');
    const text = document.getElementById('profile-or-ability');

    const prof = document.getElementById('main-profile');
    const abil = document.getElementById('special-abilities');

    btn.addEventListener('click', () => {
        prof.classList.toggle('hidden');
        abil.classList.toggle('hidden');

        if (prof.classList.contains('hidden')) {
            text.textContent = '特殊能力';
        } else {
            text.textContent = 'プロフィール';
        }
    });
}
