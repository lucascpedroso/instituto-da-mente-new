/**
 * Banner de cookies (LGPD). Pixels, Analytics, Google Ads e o mapa
 * só são carregados depois que o visitante aceita.
 */
const KEY = 'idm-consent';

export function readConsent() {
    try {
        return localStorage.getItem(KEY);
    } catch {
        return null;
    }
}

function saveConsent(value) {
    try {
        localStorage.setItem(KEY, value);
    } catch {
        // navegação privada: vale apenas para esta página
    }
}

function injectScript(src) {
    const s = document.createElement('script');
    s.async = true;
    s.src = src;
    document.head.appendChild(s);
}

export function loadTrackers() {
    if (window.__trackersLoaded) return;
    window.__trackersLoaded = true;

    const cfg = window.__SITE__ || {};

    if (cfg.ga4 || cfg.ads) {
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () {
            window.dataLayer.push(arguments);
        };
        window.gtag('js', new Date());
        if (cfg.ga4) window.gtag('config', cfg.ga4);
        if (cfg.ads) window.gtag('config', cfg.ads);
        injectScript(`https://www.googletagmanager.com/gtag/js?id=${cfg.ga4 || cfg.ads}`);
    }

    if (cfg.pixel) {
        /* eslint-disable */
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        /* eslint-enable */
        window.fbq('init', cfg.pixel);
        window.fbq('track', 'PageView');
    }

    document.querySelectorAll('[data-consent-src]').forEach((el) => {
        el.src = el.dataset.consentSrc;
        el.removeAttribute('data-consent-src');
    });

    document.dispatchEvent(new CustomEvent('consent:granted'));
}

export function consent() {
    return {
        open: false,
        init() {
            const value = readConsent();
            if (value === 'granted') loadTrackers();
            else if (value === null) this.open = true;
            window.addEventListener('consent:open', () => (this.open = true));
        },
        accept() {
            saveConsent('granted');
            this.open = false;
            loadTrackers();
        },
        reject() {
            saveConsent('denied');
            this.open = false;
        },
    };
}
