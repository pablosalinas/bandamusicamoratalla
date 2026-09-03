import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Protección de imágenes en toda la web: bloquear clic derecho y arrastre
document.addEventListener('contextmenu', function (e) {
    if (e.target && (e.target.tagName === 'IMG' || e.target.closest('img') || e.target.tagName === 'PICTURE')) {
        e.preventDefault();
        return false;
    }
}, false);

document.addEventListener('dragstart', function (e) {
    if (e.target && (e.target.tagName === 'IMG' || e.target.closest('img') || e.target.tagName === 'PICTURE')) {
        e.preventDefault();
        return false;
    }
}, false);

Alpine.start();

