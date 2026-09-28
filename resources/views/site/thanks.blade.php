<x-layouts.site title="Mensagem enviada" :noindex="true">
    <section class="container-site max-w-2xl py-20 text-center sm:py-28">
        <span class="mx-auto inline-flex size-16 items-center justify-center rounded-full bg-[#177a41]/10 text-[#177a41]"><x-site.icon name="check" class="size-8" /></span>
        <h1 class="heading-lg mt-6">Recebemos sua mensagem!</h1>
        <p class="lead mt-4">
            @if ($type === 'inscricao_curso')
                Obrigado pelo interesse em nossa formação. Em breve nossa equipe entrará em contato com as informações sobre turmas e condições.
            @elseif ($type === 'agendamento')
                Obrigado pela confiança. Em breve entraremos em contato para combinar o melhor horário para você.
            @else
                Obrigado pelo contato. Responderemos o mais breve possível.
            @endif
        </p>
        <p class="mt-6 text-sm">Se preferir agilizar, fale agora com a gente pelo WhatsApp:</p>
        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ \App\Support\Site::whatsappUrl() }}" target="_blank" rel="noopener" data-track-location="obrigado" class="btn-whatsapp"><x-site.icon name="whatsapp" /> Conversar no WhatsApp</a>
            <a href="{{ route('home') }}" class="btn-outline">Voltar ao início</a>
        </div>
    </section>

    {{-- Evento de conversão (dispara somente se os cookies foram aceitos) --}}
    <script type="module">
        const fire = () => window.track && window.track('generate_lead', { lead_type: @js($type) });
        if (window.__trackersLoaded) fire(); else document.addEventListener('consent:granted', fire, { once: true });
    </script>
</x-layouts.site>
