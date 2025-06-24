import decideSE from '../audio/se/decide-button-a.mp3';
import choiceBGM from '../audio/choice/the-decision.mp3';
import selectIcon from '../images/select.png';
import randomIcon from '../images/random.png';
import { setupBgm, toggleBgm, updateBgmIcon } from './bgm.js';

document.addEventListener('DOMContentLoaded', init);

function init()  {
    initToggles();
    initAudio();
    randomCheckboxes();
}

function initToggles() {
    const nameToggle = document.getElementById('name_random');
    const nameInput = document.querySelector("input[name='name']");
    nameToggle.addEventListener('click', function() {
        nameInput.disabled = nameToggle.checked;
    });

    const birthToggle = document.getElementById('birth_year_random');
    const birthInput = document.querySelector("input[name='birth_year']");
    birthToggle.addEventListener('click', function() {
        birthInput.disabled = birthToggle.checked;
    });

    const genderToggle = document.getElementById('gender_random');
    const genderInput = document.querySelector("select[name='gender']");
    genderToggle.addEventListener('click', function() {
        genderInput.disabled = genderToggle.checked;
    });
}

function initAudio() {
    // 音
    const bgmToggle = document.getElementById('bgm-toggle');
    const se = new Audio(decideSE);
    se.volume = 0.3;

    const message = document.getElementById('bgm-message');
    setupBgm(choiceBGM);

    bgmToggle.addEventListener('click', () => {
        se.currentTime = 0;
        se.play();

        toggleBgm();
        updateBgmIcon();
        if (bgmToggle.checked) {
            bgm.play().catch(err => console.log('BGM再生失敗:', err));
            message.innerHTML = "音ありでゲームを楽しみます。";
            message.style.color = '#c12d33';
        } else {
            message.innerHTML = "音ありでゲームを楽しみますか？";
            message.style.color = 'gray';
        }
    });

    const enabled = localStorage.getItem('bgm_enabled') === 'true';
    if (enabled) {
        bgm.play().catch(err => console.log('BGM再生失敗:', err));
        message.innerHTML = "音ありでゲームを楽しみます。";
        message.style.color = 'red';
    } else {
        message.innerHTML = "音ありでゲームを楽しみますか？";
        message.style.color = 'rgb(75, 75, 75)';
    }
}

// random / select　画像
function randomCheckboxes() {
    document.querySelectorAll('.toggle-checkbox').forEach(checkbox => {
        // チェックボックスの直近コンテナ内からラベルと img を取得
        const container = checkbox.closest('.ran-sel');
        const label = container.querySelector('label');
        const img = label.querySelector('img');

        // アイコン更新関数
        function updateIcon() {
            if (checkbox.checked) {
                img.src = randomIcon;
                img.alt = 'ON';
            } else {
                img.src = selectIcon;
                img.alt = 'OFF';
            }
        }

        // 変更時に切り替え
        checkbox.addEventListener('change', updateIcon);

        // ページ読み込み時にも初期状態を反映
        updateIcon();
    });
}

