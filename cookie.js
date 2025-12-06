// ------------------- Accessibility -------------------
        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return match[2];
            return null;
        }

        function setCookie(name, value, days) {
            let d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + "=" + value + ";expires=" + d.toUTCString() + ";path=/";
        }

        function adjustFontSize(action) {
            let currentSize = parseInt(window.getComputedStyle(document.body).fontSize);
            if (action === 'increase') currentSize += 2;
            else if (action === 'decrease') currentSize = Math.max(12, currentSize - 2);

            // Apply font size to all elements including sidebar
            document.querySelectorAll('body, #content, #sidebar, #content *, #sidebar *').forEach(el => {
                el.style.fontSize = currentSize + 'px';
            });

            setCookie('font_size', currentSize + 'px', 30);
        }

        function toggleContrast() {
            document.body.classList.toggle('high-contrast');
            setCookie('high_contrast', document.body.classList.contains('high-contrast') ? 1 : 0, 30);
        }

        // Apply saved preferences
        window.addEventListener('DOMContentLoaded', () => {
            const fontSize = getCookie('font_size');
            const highContrast = getCookie('high_contrast');

            if (fontSize) {
                document.querySelectorAll('body, #content, #sidebar, #content *, #sidebar *').forEach(el => el.style.fontSize = fontSize);
            }
            if (highContrast === '1') document.body.classList.add('high-contrast');
        });