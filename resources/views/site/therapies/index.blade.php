<x-layouts.site title="Terapias e Atendimentos" description="Psicanálise individual, terapia de casal e familiar, crianças, adolescentes, idosos, atendimento LGBTQIAP+, ansiedade, depressão e traumas. Presencial em Campinas/SP ou online."
                :breadcrumbs="['Terapias e Atendimentos' => null]">
    <x-site.page-hero eyebrow="Clínica especializada" title="Terapias e atendimentos"
                      lead="Um ambiente acolhedor e confidencial para indivíduos e famílias explorarem suas questões emocionais. Atendemos todos os públicos e idades, presencialmente em Campinas/SP ou online." />

    <section class="pb-16 sm:pb-24">
        <div class="container-site">
            <div class="mb-10 grid gap-4 rounded-2xl bg-offwhite p-6 text-sm sm:grid-cols-3 sm:p-8">
                <p class="flex items-center gap-3"><x-site.icon name="clock" class="size-6 shrink-0 text-laranja-escuro" /> Sessões de 50 minutos, geralmente semanais</p>
                <p class="flex items-center gap-3"><x-site.icon name="monitor" class="size-6 shrink-0 text-laranja-escuro" /> Presencial ou online, com o mesmo sigilo</p>
                <p class="flex items-center gap-3"><x-site.icon name="calendar" class="size-6 shrink-0 text-laranja-escuro" /> Pagamento por sessão ou pacote</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($therapies as $therapy)
                    <x-site.therapy-card :therapy="$therapy" />
                @endforeach
            </div>
        </div>
    </section>

    <x-site.cta-band title="Não sabe por onde começar?" text="Conte um pouco do seu momento e indicamos o atendimento mais adequado para você ou sua família." />
</x-layouts.site>
