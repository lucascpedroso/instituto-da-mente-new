<x-layouts.site title="Contato e Agendamento" description="Agende sua sessão ou fale com o Instituto da Mente pelo WhatsApp. Rua Camargo Pimentel, 392/394 — Jardim Guanabara, Campinas/SP. Atendimento presencial e online."
                :breadcrumbs="['Contato' => null]">
    <x-site.page-hero eyebrow="Contato e agendamento" title="Estamos aqui para ouvir você"
                      lead="Agende uma sessão, peça informações sobre os cursos ou tire suas dúvidas. Respondemos com atenção e sigilo." />

    <section class="pb-16 sm:pb-24">
        <div class="container-site grid gap-10 lg:grid-cols-[1.2fr_0.8fr]">
            <div id="agendar" class="card scroll-mt-24 bg-white p-6 sm:p-10">
                <h2 class="mb-6 text-3xl">Envie uma mensagem</h2>
                <x-site.lead-form id="agendar-form" :choose-type="true" type="agendamento"
                                  :therapies="$therapies" :courses="$courses" :professionals="$professionals" submit-label="Enviar" />
            </div>

            <div class="space-y-5">
                <a href="{{ \App\Support\Site::whatsappUrl() }}" target="_blank" rel="noopener" data-track-location="contato"
                   class="card flex items-center gap-4 border-[#177a41]/30 bg-[#177a41]/5 p-6 transition hover:border-[#177a41]">
                    <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-full bg-[#177a41] text-white"><x-site.icon name="whatsapp" class="size-6" /></span>
                    <span>
                        <span class="block font-semibold text-marrom">Fale pelo WhatsApp</span>
                        <span class="text-sm">{{ \App\Models\Setting::get('phone') }} — resposta mais rápida</span>
                    </span>
                </a>

                <div class="card bg-white/70 p-6">
                    <ul class="space-y-4 text-sm">
                        <li class="flex gap-3"><x-site.icon name="map-pin" class="size-5 shrink-0 text-laranja-escuro" /><span><span class="block font-semibold text-marrom">Endereço</span>{{ \App\Models\Setting::get('address') }}<br>{{ \App\Models\Setting::get('district') }} – {{ \App\Models\Setting::get('city') }}</span></li>
                        @if (\App\Models\Setting::get('hours'))
                            <li class="flex gap-3"><x-site.icon name="clock" class="size-5 shrink-0 text-laranja-escuro" /><span><span class="block font-semibold text-marrom">Horários</span>{{ \App\Models\Setting::get('hours') }}</span></li>
                        @endif
                        @if (\App\Models\Setting::get('email'))
                            <li class="flex gap-3"><x-site.icon name="mail" class="size-5 shrink-0 text-laranja-escuro" /><span><span class="block font-semibold text-marrom">E-mail</span><a href="mailto:{{ \App\Models\Setting::get('email') }}" class="break-all hover:underline">{{ \App\Models\Setting::get('email') }}</a></span></li>
                        @endif
                        <li class="flex gap-3"><x-site.icon name="instagram" class="size-5 shrink-0 text-laranja-escuro" /><span><span class="block font-semibold text-marrom">Instagram</span><a href="{{ \App\Support\Site::instagramUrl() }}" target="_blank" rel="noopener" class="hover:underline">{{ '@'.\App\Models\Setting::get('instagram') }}</a></span></li>
                    </ul>
                </div>

                {{-- Mapa: carregado apenas após consentimento de cookies (LGPD) --}}
                <div x-data="{ loaded: false }" @consent:granted.document="loaded = true" class="overflow-hidden rounded-2xl border border-areia/60 bg-offwhite">
                    <iframe title="Mapa: localização do Instituto da Mente" class="aspect-[4/3] w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            x-show="loaded" x-cloak
                            data-consent-src="https://www.google.com/maps?q={{ rawurlencode(\App\Models\Setting::get('maps_query')) }}&output=embed"></iframe>
                    <div x-show="!loaded" class="flex aspect-[4/3] flex-col items-center justify-center gap-3 p-6 text-center text-sm">
                        <x-site.icon name="map-pin" class="size-8 text-marrom" />
                        <p>O mapa usa cookies do Google.</p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <button type="button" class="btn-outline py-2" onclick="window.dispatchEvent(new Event('consent:open'))">Permitir cookies</button>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode(\App\Models\Setting::get('maps_query')) }}" target="_blank" rel="noopener" class="btn-dark py-2">Abrir no Google Maps</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.site>
