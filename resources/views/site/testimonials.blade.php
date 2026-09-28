<x-layouts.site title="Depoimentos" description="Depoimentos de pacientes e alunos do Instituto da Mente, em Campinas/SP."
                :breadcrumbs="['Depoimentos' => null]">
    <x-site.page-hero eyebrow="Depoimentos" title="Histórias de quem passou por aqui"
                      lead="Relatos de pacientes e alunos, publicados com autorização. Cada história é única — e todas nos inspiram." />

    <section class="pb-16 sm:pb-24">
        <div class="container-site">
            @if ($testimonials->isEmpty())
                <div class="rounded-2xl bg-offwhite p-10 text-center">
                    <p class="font-serif text-2xl text-marrom">Em breve, depoimentos de pacientes e alunos por aqui.</p>
                </div>
            @else
                <div class="columns-1 gap-5 space-y-5 md:columns-2 lg:columns-3">
                    @foreach ($testimonials as $testimonial)
                        <div class="break-inside-avoid"><x-site.testimonial-card :testimonial="$testimonial" /></div>
                    @endforeach
                </div>
                {{ $testimonials->links('components.site.pagination') }}
            @endif
        </div>
    </section>

    <section id="deixe-seu-depoimento" class="section scroll-mt-20 bg-offwhite/60">
        <div class="container-site grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow mb-3">Sua experiência importa</p>
                <h2 class="heading-lg">Deixe seu depoimento</h2>
                <p class="lead mt-4">Foi paciente ou aluno do Instituto da Mente? Conte como foi. Todo depoimento passa por aprovação antes de ser publicado, e você pode usar apenas o primeiro nome ou as iniciais.</p>
            </div>
            <div class="card bg-white p-6 sm:p-8">
                @if (config('instituto.static_preview'))
                    <x-site.preview-form-notice id="depoimento-form" message="Olá! Gostaria de enviar um depoimento sobre o Instituto da Mente." />
                @elseif (session('testimonial_sent'))
                    <div class="flex items-start gap-3 rounded-xl bg-[#177a41]/10 p-5 text-marrom" role="status">
                        <x-site.icon name="check" class="size-6 shrink-0 text-[#177a41]" />
                        <p><strong>Obrigado pelo seu depoimento!</strong> Ele será revisado pela nossa equipe antes de ser publicado.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('testimonials.store') }}#deixe-seu-depoimento" class="space-y-5" x-data="{ sending: false }" @submit="sending = true">
                        @csrf
                        <x-honeypot />
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="t-name" class="field-label">Nome ou iniciais <span class="text-terracota">*</span></label>
                                <input id="t-name" name="name" required maxlength="80" value="{{ old('name') }}" class="field">
                                @error('name') <p class="field-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="t-kind" class="field-label">Você é <span class="text-terracota">*</span></label>
                                <select id="t-kind" name="kind" class="field">
                                    @foreach (\App\Models\Testimonial::KINDS as $value => $label)
                                        <option value="{{ $value }}" @selected(old('kind') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="t-content" class="field-label">Seu depoimento <span class="text-terracota">*</span></label>
                            <textarea id="t-content" name="content" rows="5" required maxlength="1500" class="field">{{ old('content') }}</textarea>
                            @error('content') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="flex items-start gap-3 text-sm leading-relaxed">
                                <input type="checkbox" name="consent" value="1" required class="mt-1 size-4 shrink-0 accent-marrom" @checked(old('consent'))>
                                <span>Autorizo o Instituto da Mente a publicar este depoimento no site e em suas redes, conforme a <a href="{{ route('privacy') }}" target="_blank" class="font-medium text-laranja-escuro underline">Política de Privacidade</a>.</span>
                            </label>
                            @error('consent') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="btn-primary" :disabled="sending">Enviar depoimento</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</x-layouts.site>
