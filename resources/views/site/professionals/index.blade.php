<x-layouts.site title="Profissionais" description="Conheça a equipe do Instituto da Mente: psicanalistas e terapeutas qualificados, comprometidos com a ética e com o cuidado de toda a família."
                :breadcrumbs="['Profissionais' => null]">
    <x-site.page-hero eyebrow="Nossa equipe" title="Profissionais qualificados, comprometidos com você"
                      lead="Uma equipe diversificada de terapeutas, comprometida com a excelência profissional e constantemente atualizada nas melhores práticas de saúde mental." />

    <section class="pb-16 sm:pb-24">
        <div class="container-site grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($professionals as $professional)
                <article class="card-link group relative flex flex-col overflow-hidden bg-white/70 {{ $professional->is_featured && $loop->first ? 'sm:col-span-2 lg:col-span-3 lg:flex-row' : '' }}">
                    <x-site.avatar :professional="$professional" class="{{ $professional->is_featured && $loop->first ? 'aspect-[4/3] lg:aspect-auto lg:w-2/5' : 'aspect-[4/3]' }} w-full" />
                    <div class="flex flex-1 flex-col p-6 {{ $professional->is_featured && $loop->first ? 'lg:justify-center lg:p-12' : '' }}">
                        @if ($professional->is_featured)
                            <p class="eyebrow mb-2">Destaque</p>
                        @endif
                        <h2 class="text-3xl"><a href="{{ route('professionals.show', $professional) }}" class="after:absolute after:inset-0">{{ $professional->name }}</a></h2>
                        <p class="mt-1 text-sm text-cinza/90">{{ $professional->profession }}@if ($professional->registration) · {{ $professional->registration }}@endif</p>
                        @if ($professional->short_bio)
                            <p class="mt-4 leading-relaxed">{{ $professional->short_bio }}</p>
                        @endif
                        @if ($professional->specialties)
                            <ul class="mt-4 flex flex-wrap gap-2">
                                @foreach (array_slice($professional->specialties, 0, 6) as $specialty)
                                    <li class="rounded-full bg-offwhite px-3 py-1 text-xs text-marrom">{{ $specialty }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <span class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-laranja-escuro">Ver perfil e agendar <x-site.icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" /></span>
                    </div>
                </article>
            @empty
                <p class="text-center sm:col-span-3">Em breve, apresentaremos aqui toda a nossa equipe.</p>
            @endforelse
        </div>
    </section>

    <x-site.cta-band />
</x-layouts.site>
