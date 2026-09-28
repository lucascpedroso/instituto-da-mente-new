import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { consent } from './consent';
import { track } from './tracking';

window.track = track;

Alpine.plugin(collapse);
Alpine.data('consent', consent);

// Máscara de telefone brasileiro: (19) 99999-9999 / (19) 3333-3333
Alpine.data('phoneMask', () => ({
    format(event) {
        const d = event.target.value.replace(/\D/g, '').slice(0, 11);
        let v = d;
        if (d.length > 10) v = `(${d.slice(0, 2)}) ${d.slice(2, 7)}-${d.slice(7)}`;
        else if (d.length > 6) v = `(${d.slice(0, 2)}) ${d.slice(2, 6)}-${d.slice(6)}`;
        else if (d.length > 2) v = `(${d.slice(0, 2)}) ${d.slice(2)}`;
        else if (d.length > 0) v = `(${d}`;
        event.target.value = v;
    },
}));

// Cliques em links de WhatsApp contam como conversão "Contact"
document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href*="wa.me"]');
    if (link) track('whatsapp_click', { location: link.dataset.trackLocation || 'site' });
});

window.Alpine = Alpine;
Alpine.start();
