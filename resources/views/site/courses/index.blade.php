<x-layouts.site title="Formação em Psicanálise e Cursos" description="Formação completa em Psicanálise Clínica pelo Tripé Psicanalítico, credenciada ao Conselho Nacional de Psicanálise Clínica, e cursos de Hipnose, Constelação Familiar, Interpretação de Desenho e Terapia de Casal e Familiar."
                :breadcrumbs="['Formação' => null]" :schema="\App\Support\Site::faqSchema($faqs)">
    <x-site.page-hero eyebrow="Instituto de Formação" title="Formação de psicanalistas com seriedade e rigor"
                      lead="Capacitamos profissionais não apenas com conhecimento, mas com a experiência prática e ética necessárias para a atuação responsável e eficaz na psicanálise." />

    @if ($flagship)
        <section class="pb-16 sm:pb-24">
            <div class="container-site">
                <div class="overflow-hidden rounded-3xl bg-marrom text-bege lg:grid lg:grid-cols-[1.2fr_0.8fr]">
                    <div class="p-8 sm:p-12">
                        <p class="eyebrow mb-3 text-laranja-claro">Carro-chefe · Credenciada ao CNPC</p>
                        <h2 class="heading-lg text-branco">{{ $flagship->title }}</h2>
                        <p class="mt-5 text-lg leading-relaxed text-bege/90">{{ $flagship->summary }}</p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('courses.show', $flagship) }}" class="btn-primary">Conhecer a formação</a>
                            <a href="{{ \App\Support\Site::whatsappUrl('Olá! Tenho interesse na '.$flagship->title.'.') }}" target="_blank" rel="noopener" data-track-location="formacao-destaque" class="btn border border-bege/40 text-branco hover:bg-branco/10"><x-site.icon name="whatsapp" /> Falar com a equipe</a>
                        </div>
                    </div>
                    <ol class="grid gap-px bg-bege/10 lg:content-center">
                        @foreach (['Teoria' => 'Conteúdo aprofundado e abrangente', 'Análise pessoal' => 'A análise do próprio formando', 'Análise supervisionada' => 'Orientação prática na clínica'] as $pillar => $text)
                            <li class="flex items-center gap-5 bg-marrom p-6 sm:px-10">
                                <span class="font-serif text-5xl text-laranja">{{ $loop->iteration }}</span>
                                <div><p class="font-serif text-2xl text-branco">{{ $pillar }}</p><p class="text-sm text-bege/90">{{ $text }}</p></div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </section>
    @endif

    @if ($courses->isNotEmpty())
        <section class="section bg-offwhite/60">
            <div class="container-site">
                <p class="eyebrow mb-3">Formações e cursos de qualificação</p>
                <h2 class="heading-lg max-w-2xl">Amplie sua atuação terapêutica</h2>
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($courses as $course)
                        <x-site.course-card :course="$course" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($faqs->isNotEmpty())
        <section class="section">
            <div class="container-site max-w-3xl">
                <h2 class="heading-lg mb-8 text-center">Dúvidas sobre a formação</h2>
                <x-site.faq-list :faqs="$faqs" />
            </div>
        </section>
    @endif

    <x-site.cta-band title="Pronto para começar sua formação?" text="Fale com a nossa equipe e receba informações sobre turmas, formato e condições." message="Olá! Gostaria de informações sobre as formações e cursos do Instituto da Mente." />
</x-layouts.site>
