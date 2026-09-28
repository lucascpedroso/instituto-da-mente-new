<x-layouts.site :title="$therapy->title" :description="$therapy->meta_description ?: $therapy->summary" :image="$therapy->imageUrl()"
                :breadcrumbs="['Terapias' => route('therapies.index'), $therapy->title => null]"
                :schema="array_merge([[
                    '@context' => 'https://schema.org',
                    '@type' => 'MedicalTherapy',
                    'name' => $therapy->title,
                    'description' => $therapy->summary,
                    'url' => route('therapies.show', $therapy),
                    'provider' => ['@id' => url('/').'#organizacao'],
                ]], \App\Support\Site::faqSchema($faqs))">
    <x-site.page-hero eyebrow="Terapias e atendimentos" :title="$therapy->title" :lead="$therapy->summary">
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <a href="#agendar" class="btn-primary">Agendar uma conversa</a>
            <a href="{{ \App\Support\Site::whatsappUrl('Olá! Gostaria de informações sobre '.$therapy->title.'.') }}" target="_blank" rel="noopener" data-track-location="terapia-hero" class="btn-whatsapp"><x-site.icon name="whatsapp" /> WhatsApp</a>
        </div>
    </x-site.page-hero>

    <section class="pb-16 sm:pb-24">
        <div class="container-site grid gap-12 lg:grid-cols-[1.35fr_0.65fr]">
            <div>
                @if ($therapy->image)
                    <img src="{{ $therapy->imageUrl() }}" alt="" class="mb-10 aspect-[16/9] w-full rounded-3xl object-cover" width="1600" height="900">
                @endif
                <div class="prose-instituto">{!! $therapy->body !!}</div>
            </div>
            <aside class="space-y-5 lg:sticky lg:top-28 lg:self-start">
                @if ($therapy->for_whom)
                    <div class="card bg-offwhite p-6">
                        <h2 class="font-sans text-sm font-semibold tracking-widest uppercase">Para quem é indicado</h2>
                        <p class="mt-3 leading-relaxed">{{ $therapy->for_whom }}</p>
                    </div>
                @endif
                <div class="card bg-white/70 p-6">
                    <ul class="space-y-3 text-sm">
                        <li class="flex gap-3"><x-site.icon name="clock" class="size-5 shrink-0 text-laranja-escuro" /> 50 minutos por sessão</li>
                        <li class="flex gap-3"><x-site.icon name="calendar" class="size-5 shrink-0 text-laranja-escuro" /> Frequência geralmente semanal</li>
                        <li class="flex gap-3"><x-site.icon name="map-pin" class="size-5 shrink-0 text-laranja-escuro" /> Presencial em Campinas/SP</li>
                        <li class="flex gap-3"><x-site.icon name="monitor" class="size-5 shrink-0 text-laranja-escuro" /> Ou online, de onde você estiver</li>
                    </ul>
                </div>
            </aside>
        </div>
    </section>

    <section id="agendar" class="section scroll-mt-20 bg-offwhite/60">
        <div class="container-site grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow mb-3">Agendamento</p>
                <h2 class="heading-lg">Vamos conversar?</h2>
                <p class="lead mt-4">Deixe seu contato e retornaremos para combinar o melhor horário. Se preferir, fale agora pelo WhatsApp.</p>
            </div>
            <div class="card bg-white p-6 sm:p-8">
                <x-site.lead-form id="agendar-form" type="agendamento" :therapy-id="$therapy->id" />
            </div>
        </div>
    </section>

    @if ($faqs->isNotEmpty())
        <section class="section">
            <div class="container-site max-w-3xl">
                <h2 class="heading-lg mb-8 text-center">Dúvidas frequentes</h2>
                <x-site.faq-list :faqs="$faqs" />
            </div>
        </section>
    @endif

    @if ($others->isNotEmpty())
        <section class="section pt-0">
            <div class="container-site">
                <h2 class="mb-8 text-3xl">Outros atendimentos</h2>
                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($others as $other)
                        <x-site.therapy-card :therapy="$other" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.site>
