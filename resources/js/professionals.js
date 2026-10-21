/**
 * Página de profissionais: carrossel (rolagem nativa com snap) + perfil em lightbox.
 * O perfil aberto fica no endereço (#slug): dá para compartilhar o link, e o botão
 * "voltar" do celular fecha o lightbox em vez de sair da página.
 */
export function professionals(slugs) {
    return {
        slugs,
        current: null,
        pushed: false,
        closing: false,
        canPrev: false,
        canNext: false,
        active: 0,

        init() {
            this.fromHash();
            window.addEventListener('popstate', () => {
                // o "voltar" disparado pelo próprio close() chega depois: já está tratado
                if (this.closing) return (this.closing = false);
                this.fromHash();
            });
            this.$nextTick(() => this.update());
        },

        fromHash() {
            const i = this.slugs.indexOf(decodeURIComponent(location.hash.slice(1)));
            this.current = i >= 0 ? i : null;
            if (this.current === null) this.pushed = false;
        },

        open(i) {
            this.current = i;
            history.pushState(null, '', `#${this.slugs[i]}`);
            this.pushed = true;
        },

        close() {
            const last = this.current;
            this.current = null;
            if (this.pushed) {
                this.closing = true;
                history.back();
            } else {
                history.replaceState(null, '', location.pathname + location.search);
            }
            this.pushed = false;
            if (last !== null) this.$nextTick(() => this.scrollToCard(last));
        },

        go(step) {
            const n = this.slugs.length;
            const fromButton = document.activeElement?.dataset?.go;
            this.current = (this.current + step + n) % n;
            history.replaceState(null, '', `#${this.slugs[this.current]}`);

            // o perfil anterior some da tela: o foco do teclado vai junto para o novo
            // (um quadro depois: o Safari devolve o foco ao <body> ao terminar o clique)
            this.$nextTick(() => requestAnimationFrame(() => {
                const profile = document.querySelector(`[data-profile="${this.slugs[this.current]}"]`);
                (fromButton ? profile?.querySelector(`[data-go="${fromButton}"]`) : profile)?.focus();
            }));
        },

        // --- carrossel ---

        cards() {
            return [...this.$refs.track.children];
        },

        step() {
            const [first, second] = this.cards();
            return second ? second.offsetLeft - first.offsetLeft : first?.offsetWidth || 1;
        },

        scrollByCards(n) {
            this.$refs.track.scrollBy({ left: n * this.step(), behavior: this.smooth() });
        },

        scrollToCard(i) {
            const track = this.$refs.track;
            const card = this.cards()[i];
            if (card) track.scrollTo({ left: card.offsetLeft - this.cards()[0].offsetLeft, behavior: this.smooth() });
        },

        smooth() {
            return matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth';
        },

        update() {
            const t = this.$refs.track;
            this.canPrev = t.scrollLeft > 4;
            this.canNext = t.scrollLeft + t.clientWidth < t.scrollWidth - 4;
            this.active = Math.min(this.slugs.length - 1, Math.round(t.scrollLeft / this.step()));
        },
    };
}
