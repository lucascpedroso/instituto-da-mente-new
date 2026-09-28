@php
    $schema = [[
        '@context' => 'https://schema.org',
        '@type' => 'Course',
        'name' => $course->title,
        'description' => $course->summary,
        'url' => route('courses.show', $course),
        'image' => $course->imageUrl(),
        'provider' => ['@type' => 'EducationalOrganization', 'name' => 'Instituto da Mente', 'sameAs' => url('/')],
        'inLanguage' => 'pt-BR',
        'hasCourseInstance' => [
            '@type' => 'CourseInstance',
            'courseMode' => match ($course->format) { 'online' => 'online', 'presencial' => 'onsite', default => 'blended' },
            'courseWorkload' => $course->workload_hours ? 'PT'.$course->workload_hours.'H' : null,
        ],
    ]];
    $whatsMessage = 'Olá! Tenho interesse no curso '.$course->title.'.';
@endphp
<x-layouts.site :title="$course->title" :description="$course->meta_description ?: $course->summary" :image="$course->imageUrl()"
                :breadcrumbs="['Formação' => route('courses.index'), $course->title => null]"
                :schema="array_merge($schema, \App\Support\Site::faqSchema($faqs))">
    <x-site.page-hero :eyebrow="$course->is_flagship ? 'Formação completa · Tripé Psicanalítico' : 'Formação e cursos'" :title="$course->title" :lead="$course->summary">
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            @if ($course->checkout_url)
                <a href="{{ $course->checkout_url }}" target="_blank" rel="noopener" class="btn-primary">Garantir minha vaga</a>
            @else
                <a href="#inscricao" class="btn-primary">Quero me inscrever</a>
            @endif
            <a href="{{ \App\Support\Site::whatsappUrl($whatsMessage) }}" target="_blank" rel="noopener" data-track-location="curso-hero" class="btn-whatsapp"><x-site.icon name="whatsapp" /> Tirar dúvidas</a>
        </div>
    </x-site.page-hero>

    <section class="pb-16 sm:pb-24">
        <div class="container-site grid gap-12 lg:grid-cols-[1.35fr_0.65fr]">
            <div>
                @if ($course->cover)
                    <img src="{{ $course->imageUrl() }}" alt="" class="mb-10 aspect-[16/9] w-full rounded-3xl object-cover" width="1600" height="900">
                @endif
                <div class="prose-instituto">{!! $course->body !!}</div>

                @if ($course->prerequisites || $course->certification)
                    <div class="mt-12 grid gap-5 sm:grid-cols-2">
                        @if ($course->prerequisites)
                            <div class="card bg-white/70 p-6">
                                <h2 class="font-sans text-sm font-semibold tracking-widest uppercase">Pré-requisitos</h2>
                                <p class="mt-3 leading-relaxed">{{ $course->prerequisites }}</p>
                            </div>
                        @endif
                        @if ($course->certification)
                            <div class="card bg-white/70 p-6">
                                <h2 class="font-sans text-sm font-semibold tracking-widest uppercase">Certificação</h2>
                                <p class="mt-3 leading-relaxed">{{ $course->certification }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <aside class="lg:sticky lg:top-28 lg:self-start">
                <div class="card bg-offwhite p-6">
                    <h2 class="font-sans text-sm font-semibold tracking-widest uppercase">Resumo do curso</h2>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div class="grid grid-cols-[1.25rem_1fr] gap-x-3"><dt class="relative col-start-2 font-semibold text-marrom"><x-site.icon name="monitor" class="absolute top-0.5 -left-8 size-5 text-laranja-escuro" />Formato</dt><dd class="col-start-2">{{ $course->formatLabel() }}</dd></div>
                        @if ($course->workload_hours)
                            <div class="grid grid-cols-[1.25rem_1fr] gap-x-3"><dt class="relative col-start-2 font-semibold text-marrom"><x-site.icon name="clock" class="absolute top-0.5 -left-8 size-5 text-laranja-escuro" />Carga horária</dt><dd class="col-start-2">{{ $course->workload_hours }} horas</dd></div>
                        @endif
                        @if ($course->duration_text)
                            <div class="grid grid-cols-[1.25rem_1fr] gap-x-3"><dt class="relative col-start-2 font-semibold text-marrom"><x-site.icon name="calendar" class="absolute top-0.5 -left-8 size-5 text-laranja-escuro" />Duração</dt><dd class="col-start-2">{{ $course->duration_text }}</dd></div>
                        @endif
                        @if ($course->is_flagship)
                            <div class="grid grid-cols-[1.25rem_1fr] gap-x-3"><dt class="relative col-start-2 font-semibold text-marrom"><x-site.icon name="award" class="absolute top-0.5 -left-8 size-5 text-laranja-escuro" />Credenciamento</dt><dd class="col-start-2">Conselho Nacional de Psicanálise Clínica</dd></div>
                        @endif
                        @if ($course->price_text)
                            <div class="grid grid-cols-[1.25rem_1fr] gap-x-3"><dt class="relative col-start-2 font-semibold text-marrom"><x-site.icon name="clipboard" class="absolute top-0.5 -left-8 size-5 text-laranja-escuro" />Investimento</dt><dd class="col-start-2">{{ $course->price_text }}</dd></div>
                        @endif
                    </dl>
                    <a href="{{ $course->checkout_url ?: '#inscricao' }}" @if ($course->checkout_url) target="_blank" rel="noopener" @endif class="btn-primary mt-6 w-full">{{ $course->checkout_url ? 'Garantir minha vaga' : 'Quero me inscrever' }}</a>
                </div>
            </aside>
        </div>
    </section>

    @if ($testimonials->isNotEmpty())
        <section class="section pt-0">
            <div class="container-site">
                <h2 class="heading-lg mb-10">O que dizem nossos alunos</h2>
                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <x-site.testimonial-card :testimonial="$testimonial" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="inscricao" class="section scroll-mt-20 bg-offwhite/60">
        <div class="container-site grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow mb-3">Inscrição</p>
                <h2 class="heading-lg">Garanta seu lugar na próxima turma</h2>
                <p class="lead mt-4">Preencha seus dados e nossa equipe enviará as informações sobre turmas, formato e condições de pagamento.</p>
            </div>
            <div class="card bg-white p-6 sm:p-8">
                <x-site.lead-form id="inscricao-form" type="inscricao_curso" :course-id="$course->id" />
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
                <h2 class="mb-8 text-3xl">Outras formações</h2>
                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($others as $other)
                        <x-site.course-card :course="$other" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.site>
