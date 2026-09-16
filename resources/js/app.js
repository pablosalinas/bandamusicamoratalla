import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Protección de recursos multimedia en toda la web: bloquear clic derecho y arrastre
document.addEventListener('contextmenu', function (e) {
    if (e.target && (e.target.matches('img, video, audio, picture, svg') || e.target.closest('img, video, audio, picture, svg'))) {
        e.preventDefault();
        return false;
    }
}, false);

document.addEventListener('dragstart', function (e) {
    if (e.target && (e.target.matches('img, video, audio, picture, svg') || e.target.closest('img, video, audio, picture, svg'))) {
        e.preventDefault();
        return false;
    }
}, false);

Alpine.start();

