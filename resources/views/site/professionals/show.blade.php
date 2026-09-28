<x-layouts.site :title="$professional->name.' — '.$professional->profession" :description="$professional->short_bio ?: $professional->name.', '.$professional->profession.' no Instituto da Mente.'" :image="$professional->imageUrl()" type="profile"
                :breadcrumbs="['Profissionais' => route('professionals.index'), $professional->name => null]"
                :schema="[[
                    '@context' => 'https://schema.org',
                    '@type' => 'Person',
                    'name' => $professional->name,
                    'jobTitle' => $professional->profession,
                    'image' => $professional->imageUrl(),
                    'description' => $professional->short_bio,
                    'knowsAbout' => $professional->specialties,
                    'worksFor' => ['@id' => url('/').'#organizacao'],
                    'url' => route('professionals.show', $professional),
                ]]">
    <section class="container-site grid gap-12 py-10 sm:py-14 lg:grid-cols-[0.75fr_1.25fr]">
        <div class="lg:sticky lg:top-28 lg:self-start">
            <x-site.avatar :professional="$professional" class="aspect-[4/5] w-full rounded-3xl" />
            <div class="mt-6 space-y-3">
                <a href="#agendar" class="btn-primary w-full">Agendar com {{ \Illuminate\Support\Str::before($professional->name, ' ') }}</a>
                <a href="{{ \App\Support\Site::whatsappUrl('Olá! Gostaria de agendar um atendimento com '.$professional->name.'.', $professional->whatsappNumber()) }}" target="_blank" rel="noopener" data-track-location="profissional" class="btn-whatsapp w-full"><x-site.icon name="whatsapp" /> WhatsApp</a>
            </div>
        </div>
        <div>
            <h1 class="heading-xl">{{ $professional->name }}</h1>
            <p class="lead mt-3">{{ $professional->profession }}@if ($professional->registration) · {{ $professional->registration }}@endif</p>

            @if ($professional->specialties)
                <ul class="mt-6 flex flex-wrap gap-2">
                    @foreach ($professional->specialties as $specialty)
                        <li class="rounded-full border border-areia bg-white px-3 py-1 text-sm text-marrom">{{ $specialty }}</li>
                    @endforeach
                </ul>
            @endif

            @if ($professional->bio)
                <div class="prose-instituto mt-10">{!! $professional->bio !!}</div>
            @endif

            @if ($professional->education)
                <div class="card mt-10 bg-offwhite p-7">
                    <h2 class="text-3xl">Formação e especializações</h2>
                    <div class="prose-instituto mt-4">{!! $professional->education !!}</div>
                </div>
            @endif

            @if ($posts->isNotEmpty())
                <div class="mt-12">
                    <h2 class="mb-6 text-3xl">Artigos no blog</h2>
                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ($posts as $post)
                            <x-site.post-card :post="$post" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section id="agendar" class="section scroll-mt-20 bg-offwhite/60">
        <div class="container-site grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow mb-3">Agendamento</p>
                <h2 class="heading-lg">Agende com {{ $professional->name }}</h2>
                <p class="lead mt-4">Deixe seu contato e retornaremos para combinar o melhor horário, presencial ou online.</p>
            </div>
            <div class="card bg-white p-6 sm:p-8">
                <x-site.lead-form id="agendar-form" type="agendamento" :professional-id="$professional->id" />
            </div>
        </div>
    </section>
</x-layouts.site>
