let bgm;
let isBgmEnabled = localStorage.getItem('bgm_enabled') === 'true';

// ページ内で使用する場合は、明示的に呼び出す
function setupBgm(audioPath) {
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

function toggleBgm() {
    const enabled = localStorage.getItem('bgm_enabled') === 'true';
    const newState = !enabled;
    localStorage.setItem('bgm_enabled', newState);

    if (newState) {
        bgm.play().catch(err => console.log('BGM再生失敗:', err));
    } else {
        bgm.pause();
    }
}
