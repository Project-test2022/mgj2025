import redOn from '../images/icon/red_on.png';
import grayOff from '../images/icon/gray_off.png';
import decideSE from "../audio/se/decide-button-a.mp3";

let bgm;
let isBgmEnabled = localStorage.getItem('bgm_enabled') === 'true';

// ページ内で使用する場合は、明示的に呼び出す
export function setupBgm(audioPath) {
    bgm = new Audio(audioPath);
    bgm.loop = true;
    bgm.volume = 0.3;

    if (isBgmEnabled) {
        document.body.addEventListener('click', function playOnce() {
            bgm.play().catch(e => console.log('BGM再生失敗', e));
            document.body.removeEventListener('click', playOnce);
        });
    }
}

export function toggleBgm() {
    const enabled = localStorage.getItem('bgm_enabled') === 'true';
    const newState = !enabled;
    localStorage.setItem('bgm_enabled', newState);

    if (newState) {
        bgm.play().catch(err => console.log('BGM再生失敗:', err));
    } else {
        bgm.pause();
    }
}

export function updateBgmIcon() {
    const icon = document.getElementById('bgm-icon');
    const enabled = localStorage.getItem('bgm_enabled') === 'true';
    icon.src = enabled ? redOn : grayOff;
}

export function initAudio(bgm) {
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
