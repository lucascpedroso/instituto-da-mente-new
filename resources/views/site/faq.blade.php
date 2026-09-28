<x-layouts.site title="Dúvidas Frequentes" description="Como funcionam as sessões? A terapia é para sempre? É presencial ou online? Tire suas dúvidas sobre os atendimentos e a formação do Instituto da Mente."
                :breadcrumbs="['Dúvidas frequentes' => null]" :schema="\App\Support\Site::faqSchema($groups->flatten())">
    <x-site.page-hero eyebrow="Dúvidas frequentes" title="Perguntas que ouvimos com carinho"
                      lead="É natural ter dúvidas antes de começar. Reunimos aqui as perguntas mais comuns — e, se a sua não estiver na lista, fale com a gente." />

    <section class="pb-16 sm:pb-24">
        <div class="container-site max-w-3xl space-y-14">
            @foreach (\App\Models\Faq::GROUPS as $key => $label)
                @if ($groups->has($key))
                    <div>
                        <h2 class="mb-6 text-3xl">{{ $label }}</h2>
                        <x-site.faq-list :faqs="$groups[$key]" />
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    <x-site.cta-band title="Ainda ficou com alguma dúvida?" text="Nossa equipe responde pelo WhatsApp com toda a atenção." />
</x-layouts.site>
