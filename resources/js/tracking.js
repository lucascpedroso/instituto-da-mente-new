/**
 * Eventos de conversão para Meta Pixel, GA4 e Google Ads.
 * Não faz nada se o visitante não aceitou os cookies.
 */
const META_EVENTS = {
    whatsapp_click: 'Contact',
    generate_lead: 'Lead',
};

export function track(event, params = {}) {
    const cfg = window.__SITE__ || {};

    if (window.fbq && META_EVENTS[event]) {
        window.fbq('track', META_EVENTS[event], params);
    }

    if (window.gtag) {
        window.gtag('event', event, params);

        if (event === 'generate_lead' && cfg.ads && cfg.adsLabel) {
            window.gtag('event', 'conversion', { send_to: `${cfg.ads}/${cfg.adsLabel}` });
        }
    }
}
