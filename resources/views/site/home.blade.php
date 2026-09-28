<x-layouts.site :schema="\App\Support\Site::faqSchema($faqs)">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-offwhite to-branco">
        <div class="container-site grid items-center gap-12 py-14 sm:py-20 lg:grid-cols-[1.15fr_0.85fr] lg:py-24">
            <div>
                <p class="eyebrow mb-5">Clínica e Instituto de Formação · Campinas/SP e online</p>
                <h1 class="heading-xl">Terapia para <em class="text-laranja-escuro not-italic sm:italic">toda a família</em></h1>
                <p class="lead mt-6 max-w-xl">
                    Um espaço acolhedor, profissional e discreto para cuidar da sua saúde emocional — e um instituto
                    comprometido com a formação de novos psicanalistas pelo Tripé Psicanalítico.
                </p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('contact') }}#agendar" class="btn-primary">Agendar sessão <x-site.icon name="arrow-right" class="size-4" /></a>
                    <a href="{{ route('courses.index') }}" class="btn-outline">Conhecer os cursos</a>
                </div>
                <ul class="mt-10 grid gap-3 text-sm sm:grid-cols-3">
                    <li class="flex items-center gap-2"><x-site.icon name="award" class="size-5 text-laranja-escuro" /> Credenciado ao CNPC</li>
                    <li class="flex items-center gap-2"><x-site.icon name="monitor" class="size-5 text-laranja-escuro" /> Presencial e online</li>
                    <li class="flex items-center gap-2"><x-site.icon name="users" class="size-5 text-laranja-escuro" /> Todas as idades</li>
                </ul>
            </div>
            <div class="relative mx-auto w-full max-w-md">
                <div class="absolute inset-0 -rotate-3 rounded-[2.5rem] bg-bege/60"></div>
                <div class="relative rounded-[2.5rem] border border-areia/60 bg-branco p-10 shadow-2xl shadow-marrom/10">
                    <img src="{{ asset('images/logo-laranja.png') }}" alt="" aria-hidden="true" width="201" height="110" class="mx-auto w-full max-w-72">
                    <p class="mt-8 text-center font-serif text-2xl leading-snug text-marrom italic">
                        “Profissionais qualificados, responsabilidade e valorização humana.”
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Dois pilares --}}
    <section class="section">
        <div class="container-site">
            <div class="max-w-2xl">
                <p class="eyebrow mb-3">Duas missões essenciais</p>
                <h2 class="heading-lg">Cuidado emocional e formação de psicanalistas</h2>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-2">
                <div class="card flex flex-col bg-white/70 p-8 sm:p-10">
                    <span class="mb-6 inline-flex size-14 items-center justify-center rounded-2xl bg-offwhite text-laranja-escuro"><x-site.icon name="heart" class="size-7" /></span>
                    <h3 class="text-3xl">Clínica especializada</h3>
                    <p class="mt-3 flex-1 leading-relaxed">Terapia individual, de casal e familiar, com crianças, adolescentes e idosos, atendimento LGBTQIAP+ e programas para ansiedade, depressão e traumas. Uma abordagem centrada no paciente, que respeita a singularidade de cada pessoa.</p>
                    <a href="{{ route('therapies.index') }}" class="btn-dark mt-8 self-start">Ver terapias e atendimentos</a>
                </div>
                <div class="card flex flex-col bg-marrom p-8 text-bege sm:p-10">
                    <span class="mb-6 inline-flex size-14 items-center justify-center rounded-2xl bg-branco/10 text-laranja"><x-site.icon name="graduation" class="size-7" /></span>
                    <h3 class="text-3xl text-branco">Formação em Psicanálise</h3>
                    <p class="mt-3 flex-1 leading-relaxed text-bege/90">Formação completa pelo Tripé Psicanalítico — Teoria, Análise Pessoal e Análise Supervisionada — com material didático próprio e credenciamento ao Conselho Nacional de Psicanálise Clínica.</p>
                    <a href="{{ route('courses.index') }}" class="btn-primary mt-8 self-start">Conhecer a formação</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Terapias --}}
    @if ($therapies->isNotEmpty())
        <section class="section bg-offwhite/60">
            <div class="container-site">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                    <div class="max-w-2xl">
                        <p class="eyebrow mb-3">Terapias e atendimentos</p>
                        <h2 class="heading-lg">Cuidado para cada fase e cada história</h2>
                        <p class="lead mt-4">Sessões de 50 minutos, geralmente semanais, presenciais em Campinas ou online.</p>
                    </div>
                    <a href="{{ route('therapies.index') }}" class="btn-outline shrink-0">Ver todas</a>
                </div>
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($therapies as $therapy)
                        <x-site.therapy-card :therapy="$therapy" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Formação (carro-chefe) --}}
    @if ($flagship)
        <section class="section">
            <div class="container-site grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <p class="eyebrow mb-3">Formação completa</p>
                    <h2 class="heading-lg">{{ $flagship->title }}</h2>
                    <p class="lead mt-5">{{ $flagship->summary }}</p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('courses.show', $flagship) }}" class="btn-primary">Quero saber mais</a>
                        <a href="{{ \App\Support\Site::whatsappUrl('Olá! Tenho interesse na '.$flagship->title.'.') }}" target="_blank" rel="noopener" data-track-location="home-formacao" class="btn-whatsapp"><x-site.icon name="whatsapp" /> Tirar dúvidas</a>
                    </div>
                </div>
                <ol class="grid gap-4">
                    @foreach ([['Teoria', 'Conteúdo aprofundado e abrangente, com material didático próprio.'], ['Análise pessoal', 'A experiência de análise do próprio formando.'], ['Análise supervisionada', 'Orientação prática no atendimento clínico.']] as $i => [$pillar, $text])
                        <li class="card flex items-start gap-5 bg-white/70 p-6">
                            <span class="font-serif text-5xl leading-none text-laranja-escuro">{{ $i + 1 }}</span>
                            <div>
                                <h3 class="text-2xl">{{ $pillar }}</h3>
                                <p class="mt-1 text-sm leading-relaxed">{{ $text }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
            @if ($courses->isNotEmpty())
                <div class="container-site mt-14">
                    <h3 class="mb-5 font-sans text-sm font-semibold tracking-widest text-marrom uppercase">Outras formações e cursos</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($courses as $course)
                            <a href="{{ route('courses.show', $course) }}" class="rounded-full border border-areia bg-white px-4 py-2 text-sm text-marrom transition hover:border-marrom">{{ $course->title }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endif

    {{-- Fundador --}}
    @if ($founder)
        <section class="section bg-marrom text-bege">
            <div class="container-site grid items-center gap-12 md:grid-cols-[0.8fr_1.2fr]">
                <x-site.avatar :professional="$founder" class="mx-auto aspect-[4/5] w-full max-w-sm rounded-3xl" />
                <div>
                    <p class="eyebrow mb-3 text-laranja-claro">Fundador</p>
                    <h2 class="heading-lg text-branco">{{ $founder->name }}</h2>
                    <p class="mt-2 text-bege/90">{{ $founder->profession }}</p>
                    <p class="mt-6 text-lg leading-relaxed text-bege/90">{{ $founder->short_bio }}</p>
                    <ul class="mt-6 flex flex-wrap gap-2">
                        @foreach ($founder->specialties ?? [] as $specialty)
                            <li class="rounded-full border border-bege/25 px-3 py-1 text-sm">{{ $specialty }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('professionals.show', $founder) }}" class="btn-primary mt-8">Conhecer o profissional</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Depoimentos --}}
    @if ($testimonials->isNotEmpty())
        <section class="section">
            <div class="container-site">
                <div class="max-w-2xl">
                    <p class="eyebrow mb-3">Depoimentos</p>
                    <h2 class="heading-lg">Quem passou por aqui</h2>
                </div>
                <div class="mt-12 grid gap-5 md:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <x-site.testimonial-card :testimonial="$testimonial" />
                    @endforeach
                </div>
                <a href="{{ route('testimonials.index') }}" class="btn-outline mt-10">Ver todos os depoimentos</a>
            </div>
        </section>
    @endif

    {{-- Livro + Blog --}}
    @if ($book || $posts->isNotEmpty())
        <section class="section bg-offwhite/60">
            <div class="container-site grid gap-12 lg:grid-cols-[0.8fr_1.2fr]">
                @if ($book)
                    <div class="flex flex-col items-start gap-8 sm:flex-row lg:flex-col">
                        <a href="{{ route('books.show', $book) }}" class="block w-44 shrink-0 transition hover:-translate-y-1 sm:w-52">
                            <x-site.book-cover :book="$book" />
                        </a>
                        <div>
                            <p class="eyebrow mb-3">Livro do fundador</p>
                            <h2 class="text-4xl">{{ $book->title }}</h2>
                            <p class="mt-2 text-sm">{{ $book->author }}</p>
                            <p class="mt-4 leading-relaxed">{{ $book->synopsis }}</p>
                            <a href="{{ route('books.show', $book) }}" class="btn-outline mt-6">Conhecer o livro</a>
                        </div>
                    </div>
                @endif
                @if ($posts->isNotEmpty())
                    <div>
                        <div class="mb-8 flex items-end justify-between gap-4">
                            <div>
                                <p class="eyebrow mb-3">Blog</p>
                                <h2 class="text-4xl">Conteúdos para refletir</h2>
                            </div>
                            <a href="{{ route('blog.index') }}" class="shrink-0 text-sm font-semibold text-laranja-escuro hover:underline">Ver todos</a>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($posts as $post)
                                <x-site.post-card :post="$post" />
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    @if ($faqs->isNotEmpty())
        <section class="section">
            <div class="container-site grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
                <div>
                    <p class="eyebrow mb-3">Dúvidas frequentes</p>
                    <h2 class="heading-lg">Antes de começar</h2>
                    <p class="lead mt-4">Respondemos as perguntas mais comuns de quem está pensando em iniciar a terapia ou a formação.</p>
                    <a href="{{ route('faq') }}" class="btn-outline mt-8">Ver todas as dúvidas</a>
                </div>
                <x-site.faq-list :faqs="$faqs" />
            </div>
        </section>
    @endif

    <x-site.cta-band />
</x-layouts.site>
