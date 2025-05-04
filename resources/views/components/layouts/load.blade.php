<!-- Loading Overlay -->
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0;
     width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999;
     justify-content: center; align-items: center;">
    <div class="spinner"></div>
</div>

<style>
    .spinner {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // フォーム送信時にローディングを表示
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function () {
                document.getElementById('loading-overlay').style.display = 'flex';
            });
        });

        // 通常リンククリック時も対象
        const links = document.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('#') && !link.hasAttribute('target')) {
                    document.getElementById('loading-overlay').style.display = 'flex';
                }
            });
        });

        // ボタンクリック時も対象
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('loading-overlay').style.display = 'flex';
            });
        });
    });

    window.addEventListener('pageshow', function () {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    });
</script>
