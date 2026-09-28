@props([
    'title' => 'Dê o primeiro passo',
    'text' => 'Fale com a gente e agende uma conversa inicial. Atendimento presencial em Campinas/SP ou online.',
    'message' => null,
])
<section class="section pt-0">
    <div class="container-site">
        <div class="relative overflow-hidden rounded-3xl bg-marrom px-6 py-12 text-center sm:px-12 sm:py-16">
            <img src="{{ asset('images/logo-branco.png') }}" alt="" aria-hidden="true" class="pointer-events-none absolute -top-6 -left-10 w-56 opacity-[0.06]" loading="lazy">
            <h2 class="heading-lg text-branco">{{ $title }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-bege/90">{{ $text }}</p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ \App\Support\Site::whatsappUrl($message) }}" target="_blank" rel="noopener" data-track-location="cta" class="btn-whatsapp"><x-site.icon name="whatsapp" /> Conversar no WhatsApp</a>
                <a href="{{ route('contact') }}#agendar" class="btn border border-bege/40 text-branco hover:bg-branco/10">Enviar mensagem</a>
            </div>
        </div>
    </div>
</section>
