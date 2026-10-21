<x-layouts.site title="Profissionais" description="Conheça a equipe do Instituto da Mente: psicanalistas e terapeutas qualificados, comprometidos com a ética e com o cuidado de toda a família."
                :breadcrumbs="['Profissionais' => null]">
    <x-site.page-hero eyebrow="Nossa equipe" title="Profissionais qualificados, comprometidos com você"
                      lead="Uma equipe diversificada de terapeutas, comprometida com a excelência profissional e constantemente atualizada nas melhores práticas de saúde mental." />

    <section class="pb-16 sm:pb-24">
        <div class="container-site">
            @if ($professionals->isEmpty())
                <p class="text-center">Em breve, apresentaremos aqui toda a nossa equipe.</p>
            @else
                <div x-data="professionals(@js($professionals->pluck('slug')))" @resize.window.debounce.100ms="update()">
                    {{-- Controles do carrossel: só aparecem quando há mais profissionais do que cabem na tela --}}
                    <div class="mb-6 flex items-center justify-between gap-4" x-cloak x-show="canPrev || canNext">
                        <p class="text-sm text-cinza/90">{{ $professionals->count() }} profissionais<span class="hidden sm:inline"> · clique em um card para conhecer</span></p>
                        <div class="flex gap-2">
                            <button type="button" @click="scrollByCards(-1)" :disabled="!canPrev" aria-label="Profissionais anteriores"
                                    class="inline-flex size-11 items-center justify-center rounded-full border border-areia bg-white text-marrom transition hover:border-marrom disabled:opacity-40 disabled:hover:border-areia">
                                <x-site.icon name="chevron-right" class="size-5 rotate-180" />
                            </button>
                            <button type="button" @click="scrollByCards(1)" :disabled="!canNext" aria-label="Próximos profissionais"
                                    class="inline-flex size-11 items-center justify-center rounded-full border border-areia bg-white text-marrom transition hover:border-marrom disabled:opacity-40 disabled:hover:border-areia">
                                <x-site.icon name="chevron-right" class="size-5" />
                            </button>
                        </div>
                    </div>

                    <ul x-ref="track" @scroll.debounce.50ms="update()" aria-label="Profissionais do Instituto"
                        class="relative -mx-4 flex snap-x snap-mandatory scroll-px-4 gap-5 overflow-x-auto px-4 pt-1 pb-4 [scrollbar-width:none] sm:mx-0 sm:scroll-px-0 sm:px-0 [&::-webkit-scrollbar]:hidden">
                        @foreach ($professionals as $professional)
                            <li class="w-[82%] shrink-0 snap-start sm:w-[calc((100%-1.25rem)/2)] lg:w-[calc((100%-2.5rem)/3)]">
                                <button type="button" @click="open({{ $loop->index }})" aria-haspopup="dialog" data-professional="{{ $professional->slug }}"
                                        class="card-link group flex h-full w-full flex-col overflow-hidden bg-white/70 text-left">
                                    <span class="relative block overflow-hidden">
                                        <x-site.avatar :professional="$professional" class="aspect-[4/3] w-full object-[center_20%] transition duration-500 group-hover:scale-[1.03] sm:aspect-square" />
                                        @if ($professional->is_featured)
                                            <span class="absolute top-4 left-4 rounded-full bg-branco/90 px-3 py-1 text-xs font-semibold text-marrom">Fundador</span>
                                        @endif
                                    </span>
                                    <span class="flex flex-1 flex-col p-6">
                                        <span class="font-serif text-3xl leading-tight font-semibold text-marrom">{{ $professional->name }}</span>
                                        <span class="mt-1 text-sm text-cinza/90">{{ $professional->profession }}</span>
                                        @if ($professional->short_bio)
                                            <span class="mt-4 line-clamp-3 leading-relaxed">{{ $professional->short_bio }}</span>
                                        @endif
                                        @if ($professional->specialties)
                                            <span class="mt-4 flex flex-wrap gap-2">
                                                @foreach (array_slice($professional->specialties, 0, 3) as $specialty)
                                                    <span class="rounded-full bg-offwhite px-3 py-1 text-xs text-marrom">{{ $specialty }}</span>
                                                @endforeach
                                            </span>
                                        @endif
                                        <span class="mt-auto inline-flex items-center gap-1 pt-6 text-sm font-semibold text-laranja-escuro">
                                            Conhecer {{ \Illuminate\Support\Str::before($professional->name, ' ') }}
                                            <x-site.icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" />
                                        </span>
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4 flex justify-center gap-2" x-cloak x-show="canPrev || canNext">
                        @foreach ($professionals as $professional)
                            <button type="button" @click="scrollToCard({{ $loop->index }})" aria-label="Ir para {{ $professional->name }}"
                                    class="h-2 rounded-full transition-all" :class="active === {{ $loop->index }} ? 'w-6 bg-marrom' : 'w-2 bg-areia hover:bg-marrom-claro'"></button>
                        @endforeach
                    </div>

                    {{-- Perfil em lightbox (levado para o <body> para ficar acima do cabeçalho e do aviso de cookies) --}}
                    <template x-teleport="body">
                        <div x-cloak x-show="current !== null" x-modal="current !== null"
                             @keydown.escape.window="current !== null && close()"
                             @keydown.arrow-left.window="current !== null && slugs.length > 1 && go(-1)" @keydown.arrow-right.window="current !== null && slugs.length > 1 && go(1)"
                             class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center sm:p-6"
                             role="dialog" aria-modal="true" :aria-labelledby="current !== null && `perfil-${slugs[current]}`">
                            <div x-show="current !== null" x-transition.opacity.duration.200ms class="absolute inset-0 bg-preto/60 backdrop-blur-sm" @click="close()"></div>

                            <div x-show="current !== null"
                                 x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-full opacity-0 sm:translate-y-6"
                                 x-transition:leave="transition duration-200 ease-in" x-transition:leave-end="translate-y-full opacity-0 sm:translate-y-6"
                                 class="relative w-full max-w-4xl">
                                <button type="button" @click="close()" aria-label="Fechar perfil"
                                        class="absolute top-3 right-3 z-10 inline-flex size-11 items-center justify-center rounded-full bg-branco/90 text-marrom shadow-lg backdrop-blur transition hover:bg-branco">
                                    <x-site.icon name="x" class="size-6" />
                                </button>

                                @foreach ($professionals as $professional)
                                    @php $firstName = \Illuminate\Support\Str::before($professional->name, ' '); @endphp
                                    <article x-show="current === {{ $loop->index }}" data-profile="{{ $professional->slug }}" tabindex="-1"
                                             class="flex max-h-[92dvh] flex-col overflow-y-auto overscroll-contain rounded-t-3xl bg-branco shadow-2xl outline-none sm:rounded-3xl md:grid md:h-[min(42rem,88dvh)] md:max-h-none md:grid-cols-[2fr_3fr] md:grid-rows-[minmax(0,1fr)] md:overflow-hidden">
                                        <div class="relative aspect-[16/9] shrink-0 md:aspect-auto">
                                            <x-site.avatar :professional="$professional" class="absolute inset-0 size-full object-[center_20%]" />
                                        </div>

                                        <div class="flex min-h-0 flex-col md:overflow-y-auto md:overscroll-contain">
                                            <div class="flex-1 p-6 sm:p-10">
                                                <p class="eyebrow mb-3">{{ $professional->is_featured ? 'Fundador' : 'Equipe Instituto da Mente' }}</p>
                                                <h2 id="perfil-{{ $professional->slug }}" class="heading-lg">{{ $professional->name }}</h2>
                                                <p class="mt-2 text-cinza/90">{{ $professional->profession }}@if ($professional->registration) · {{ $professional->registration }}@endif</p>

                                                @if ($professional->specialties)
                                                    <ul class="mt-5 flex flex-wrap gap-2" aria-label="Especialidades">
                                                        @foreach ($professional->specialties as $specialty)
                                                            <li class="rounded-full border border-areia bg-white px-3 py-1 text-sm text-marrom">{{ $specialty }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                @if ($professional->bio)
                                                    <div class="prose-instituto mt-7">{!! $professional->bio !!}</div>
                                                @elseif ($professional->short_bio)
                                                    <p class="mt-7 leading-relaxed">{{ $professional->short_bio }}</p>
                                                @endif

                                                @if ($professional->education)
                                                    <div class="mt-7 rounded-2xl bg-offwhite p-6">
                                                        <h3 class="text-2xl">Formação e especializações</h3>
                                                        <div class="prose-instituto mt-3">{!! $professional->education !!}</div>
                                                    </div>
                                                @endif

                                                <a href="{{ route('professionals.show', $professional) }}" class="mt-7 inline-flex items-center gap-1 text-sm font-semibold text-laranja-escuro hover:underline">
                                                    Ver perfil completo <x-site.icon name="arrow-right" class="size-4" />
                                                </a>
                                            </div>

                                            <div class="sticky bottom-0 border-t border-areia/60 bg-branco px-6 py-3 sm:px-10 sm:py-4">
                                                <div class="flex gap-2">
                                                    <a href="{{ route('professionals.show', $professional) }}#agendar" class="btn-primary flex-1 px-4">Agendar com {{ $firstName }}</a>
                                                    <a href="{{ \App\Support\Site::whatsappUrl('Olá! Gostaria de agendar um atendimento com '.$professional->name.'.', $professional->whatsappNumber()) }}"
                                                       target="_blank" rel="noopener" data-track-location="profissionais-lightbox" class="btn-whatsapp flex-1 px-4"><x-site.icon name="whatsapp" /> WhatsApp</a>
                                                </div>
                                                @if ($professionals->count() > 1)
                                                    <div class="mt-2 flex items-center justify-between text-sm text-cinza/90">
                                                        <button type="button" @click="go(-1)" data-go="-1" class="inline-flex items-center gap-1 rounded-full px-2 py-1 hover:text-marrom">
                                                            <x-site.icon name="chevron-right" class="size-4 rotate-180" /> Anterior
                                                        </button>
                                                        <span aria-live="polite">{{ $loop->iteration }} de {{ $professionals->count() }}</span>
                                                        <button type="button" @click="go(1)" data-go="1" class="inline-flex items-center gap-1 rounded-full px-2 py-1 hover:text-marrom">
                                                            Próximo <x-site.icon name="chevron-right" class="size-4" />
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </article>
                                @endforeach

                            </div>
                        </div>
                    </template>
                </div>
            @endif
        </div>
    </section>

    <x-site.cta-band />
</x-layouts.site>
