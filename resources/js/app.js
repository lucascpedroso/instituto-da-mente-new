import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import { consent } from './consent';
import { track } from './tracking';

window.track = track;

Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.data('consent', consent);

// Máscara de telefone brasileiro: (19) 99999-9999 / (19) 3333-3333
Alpine.data('phoneMask', () => ({
    format(event) {
        let d = event.target.value.replace(/\D/g, '');
        // número colado com o código do país, como o WhatsApp copia: +55 19 99999-9999
        if (d.length > 11 && d.startsWith('55')) d = d.slice(2);
        d = d.slice(0, 11);
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
