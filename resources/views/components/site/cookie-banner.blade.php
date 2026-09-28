<div x-data="consent" x-cloak x-show="open" x-transition.opacity
     class="fixed inset-x-3 bottom-3 z-50 mx-auto max-w-3xl rounded-2xl border border-areia bg-branco p-5 shadow-2xl shadow-marrom/20 sm:inset-x-6 sm:bottom-6"
     role="dialog" aria-live="polite" aria-label="Aviso de cookies">
    <p class="text-sm leading-relaxed text-cinza">
        Usamos cookies essenciais para o funcionamento do site e, com a sua permissão, cookies de medição e marketing
        (Google e Meta) para entender o uso do site e melhorar nossas campanhas. Saiba mais na nossa
        <a href="{{ route('privacy') }}" class="font-medium text-laranja-escuro underline">Política de Privacidade</a>.
    </p>
    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:justify-end">
        <button type="button" @click="reject()" class="btn-outline">Somente essenciais</button>
        <button type="button" @click="accept()" class="btn-dark">Aceitar todos</button>
    </div>
</div>
