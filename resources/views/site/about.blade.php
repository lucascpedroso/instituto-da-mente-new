<x-layouts.site title="Sobre o Instituto" description="Conheça o Instituto da Mente: fundado em novembro de 2023 por Ricardo Mello, em Campinas/SP. Clínica especializada em terapia individual e familiar e instituto de formação de psicanalistas."
                :breadcrumbs="['Sobre o Instituto' => null]">
    <x-site.page-hero eyebrow="Sobre o Instituto" title="Um centro de referência em saúde emocional e formação"
                      lead="O Instituto da Mente foi fundado em novembro de 2023 por Ricardo Mello, em Campinas/SP, e se dedica a duas missões essenciais: o cuidado da saúde emocional e a formação de novos psicanalistas." />

    <section class="section pt-0">
        <div class="container-site grid gap-12 lg:grid-cols-2">
            <div class="prose-instituto">
                <h2>Nossa história</h2>
                <p>Desde sempre, nosso fundador quis construir algo para ajudar as pessoas — e encontrou na psicanálise essa oportunidade. Após anos de prática clínica, nasceu o Instituto da Mente: um espaço acolhedor e confidencial para indivíduos e famílias explorarem suas questões emocionais e psicológicas.</p>
                <p>Acreditamos profundamente no impacto transformador de uma terapia bem orientada. Nossa abordagem é centrada no paciente, respeitando a singularidade de cada indivíduo, com uma equipe de terapeutas diversificada, comprometida com a excelência profissional e constantemente atualizada nas melhores práticas de saúde mental.</p>
                <p>Ao mesmo tempo, somos um Instituto de Formação de Psicanalistas que leva a sério os pilares estabelecidos por Sigmund Freud: o Tripé Psicanalítico.</p>
            </div>
            <div class="grid gap-5 self-start">
                <div class="card bg-white/70 p-7">
                    <p class="eyebrow mb-2">Missão</p>
                    <p class="leading-relaxed">Promover o bem-estar emocional e psicológico de indivíduos, famílias e profissionais por meio de terapias eficazes, cursos de especialização, supervisão e palestras. Nosso objetivo é oferecer um ambiente acolhedor, profissional e discreto, que facilite o florescimento emocional e fortaleça vínculos familiares. Valorizamos a inclusão e o apoio terapêutico abrangente, buscando continuamente proporcionar crescimento pessoal e profissional para todos os nossos clientes e colaboradores.</p>
                </div>
                <div class="card bg-white/70 p-7">
                    <p class="eyebrow mb-2">Visão</p>
                    <p class="leading-relaxed">Ser reconhecido como um Instituto de excelência em terapias eficazes, cursos de especialização, supervisão e palestras. Buscamos criar um ambiente onde a busca pelo desenvolvimento pessoal e profissional seja estimulada, contribuindo para comunidades mais saudáveis e conectadas.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-offwhite/60">
        <div class="container-site">
            <p class="eyebrow mb-3">Valores</p>
            <h2 class="heading-lg max-w-2xl">O que orienta cada atendimento e cada aula</h2>
            <ul class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['heart', 'Empatia e compreensão'],
                    ['book', 'Aprendizado contínuo'],
                    ['shield', 'Integridade e ética profissional'],
                    ['users', 'Foco na família'],
                    ['sprout', 'Inovação terapêutica'],
                    ['rainbow', 'Acessibilidade e inclusão'],
                ] as [$icon, $value])
                    <li class="card flex items-center gap-4 bg-white/80 p-6">
                        <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-2xl bg-offwhite text-laranja-escuro"><x-site.icon :name="$icon" class="size-6" /></span>
                        <span class="font-serif text-2xl text-marrom">{{ $value }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="section">
        <div class="container-site">
            <p class="eyebrow mb-3">Diferenciais da nossa formação</p>
            <h2 class="heading-lg max-w-2xl">Seriedade e rigor em cada etapa</h2>
            <div class="mt-12 grid gap-5 md:grid-cols-3">
                <div class="card bg-white/70 p-7">
                    <x-site.icon name="book" class="size-8 text-laranja-escuro" />
                    <h3 class="mt-4 text-2xl">Conteúdo próprio</h3>
                    <p class="mt-2 leading-relaxed">Material didático próprio, baseado no livro <em>Psicanálise</em>, escrito pelo nosso fundador, Ricardo Mello.</p>
                </div>
                <div class="card bg-white/70 p-7">
                    <x-site.icon name="graduation" class="size-8 text-laranja-escuro" />
                    <h3 class="mt-4 text-2xl">Corpo docente</h3>
                    <p class="mt-2 leading-relaxed">Professores competentes e capacitados, com vasta experiência clínica e acadêmica.</p>
                </div>
                <div class="card bg-white/70 p-7">
                    <x-site.icon name="award" class="size-8 text-laranja-escuro" />
                    <h3 class="mt-4 text-2xl">Credenciamento</h3>
                    <p class="mt-2 leading-relaxed">Validados e credenciados ao Conselho Nacional de Psicanálise Clínica, garantindo a seriedade e o rigor da sua formação.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($founder)
        <section class="section bg-marrom text-bege">
            <div class="container-site grid items-center gap-10 md:grid-cols-[0.6fr_1.4fr]">
                <x-site.avatar :professional="$founder" class="mx-auto aspect-square w-full max-w-64 rounded-full" />
                <div>
                    <p class="eyebrow mb-3 text-laranja-claro">Fundador</p>
                    <h2 class="heading-lg text-branco">{{ $founder->name }}</h2>
                    <p class="mt-4 text-lg leading-relaxed text-bege/90">{{ $founder->short_bio }}</p>
                    <a href="{{ route('professionals.show', $founder) }}" class="btn-primary mt-7">Ver perfil completo</a>
                </div>
            </div>
        </section>
    @endif

    <section class="section pb-0">
        <div class="container-site text-center text-sm text-cinza/80">
            Instituto da Mente · CNPJ {{ \App\Models\Setting::get('cnpj') }} · {{ \App\Support\Site::fullAddress() }}
        </div>
    </section>

    <x-site.cta-band />
</x-layouts.site>
