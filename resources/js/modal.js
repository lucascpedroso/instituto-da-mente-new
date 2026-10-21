/**
 * x-modal="aberto": enquanto a expressão for verdadeira, o resto da página fica `inert`
 * (sem foco, sem clique e invisível para leitores de tela — o Tab fica preso no modal),
 * a rolagem da página trava e o foco vai para o primeiro botão do modal. Ao fechar,
 * tudo é desfeito e o foco volta para onde estava. Tudo síncrono: abrir e fechar
 * rapidamente (ex.: toque duplo) não deixa a página travada.
 *
 * O elemento precisa ser filho direto do <body> (use dentro de <template x-teleport="body">).
 */
function lockPage(modal) {
    const opener = document.activeElement;
    const html = document.documentElement;
    const overflow = html.style.overflow;
    const others = [...document.body.children].filter((el) => el !== modal && !el.inert && !['SCRIPT', 'TEMPLATE'].includes(el.tagName));

    others.forEach((el) => (el.inert = true));
    html.style.overflow = 'hidden';

    return () => {
        others.forEach((el) => (el.inert = false));
        html.style.overflow = overflow;
        if (opener && opener !== document.body) opener.focus({ preventScroll: true });
    };
}

export function modal(Alpine) {
    Alpine.directive('modal', (el, { expression }, { evaluateLater, effect, cleanup }) => {
        const isOpen = evaluateLater(expression);
        let release = null;

        effect(() =>
            isOpen((open) => {
                if (open && !release) {
                    release = lockPage(el);
                    Alpine.nextTick(() => el.querySelector('button, [href], [tabindex]')?.focus());
                } else if (!open && release) {
                    release();
                    release = null;
                }
            }),
        );

        cleanup(() => release?.());
    });
}
