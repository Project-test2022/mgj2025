import decideSE from '../audio/se/decide-button-a.mp3';
import bgm from '../audio/op/high-stakes-shadow.mp3';
import { setupBgm, toggleBgm, updateBgmIcon } from './bgm.js';

document.addEventListener('DOMContentLoaded', init);

function init() {
    initProfileToggles();
    initAudio();
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

function initAudio() {
    // 効果音（ボタン用）
    document.querySelectorAll('.button').forEach(button => {
        if (button.textContent.length >= 6) {
            button.classList.add('long-text');
        }
    });

    const btns = document.querySelectorAll('.buttons');
    const se = new Audio(decideSE);
    se.volume = 0.3;
    btns.forEach(btn => {
        btn.addEventListener('click', function () {
            se.currentTime = 0;
            se.play();
        });
    });

    // 初期再生
    setupBgm(bgm);
    // 初期アイコン
    updateBgmIcon();

    // ボタンクリックで切り替え
    document.getElementById('bgm-toggle').addEventListener('click', function () {
        toggleBgm();
        updateBgmIcon();
    });
}
