@props(['settings', 'therapies', 'courses'])
<footer class="mt-auto bg-marrom text-bege">
    <div class="container-site grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        <div class="space-y-4">
            <img src="{{ asset('images/logo-branco.png') }}" alt="Instituto da Mente" width="199" height="109" class="h-16 w-auto" loading="lazy">
            <p class="text-sm leading-relaxed text-bege/85">Terapia para toda a família e formação de psicanalistas pelo Tripé Psicanalítico.</p>
            <div class="flex gap-3">
                <a href="{{ \App\Support\Site::instagramUrl() }}" target="_blank" rel="noopener" class="rounded-full border border-bege/30 p-2.5 transition hover:bg-bege/10" aria-label="Instagram {{ "@".$settings["instagram"] }}"><x-site.icon name="instagram" /></a>
                <a href="{{ \App\Support\Site::whatsappUrl() }}" target="_blank" rel="noopener" data-track-location="rodape" class="rounded-full border border-bege/30 p-2.5 transition hover:bg-bege/10" aria-label="WhatsApp"><x-site.icon name="whatsapp" /></a>
            </div>
        </div>

        <div>
            <h2 class="mb-4 font-sans text-sm font-semibold tracking-widest text-branco uppercase">Terapias</h2>
            <ul class="space-y-2 text-sm">
                @foreach ($therapies->take(7) as $t)
                    <li><a href="{{ route('therapies.show', $t) }}" class="text-bege/85 hover:text-branco">{{ $t->title }}</a></li>
                @endforeach
                <li><a href="{{ route('therapies.index') }}" class="font-medium text-branco hover:underline">Ver todas</a></li>
            </ul>
        </div>

        <div>
            <h2 class="mb-4 font-sans text-sm font-semibold tracking-widest text-branco uppercase">Instituto</h2>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('courses.index') }}" class="text-bege/85 hover:text-branco">Formação e cursos</a></li>
                <li><a href="{{ route('about') }}" class="text-bege/85 hover:text-branco">Sobre o Instituto</a></li>
                <li><a href="{{ route('professionals.index') }}" class="text-bege/85 hover:text-branco">Profissionais</a></li>
                <li><a href="{{ route('books.index') }}" class="text-bege/85 hover:text-branco">Livros</a></li>
                <li><a href="{{ route('blog.index') }}" class="text-bege/85 hover:text-branco">Blog</a></li>
                <li><a href="{{ route('testimonials.index') }}" class="text-bege/85 hover:text-branco">Depoimentos</a></li>
                <li><a href="{{ route('faq') }}" class="text-bege/85 hover:text-branco">Dúvidas frequentes</a></li>
            </ul>
        </div>

        <div>
            <h2 class="mb-4 font-sans text-sm font-semibold tracking-widest text-branco uppercase">Contato</h2>
            <ul class="space-y-3 text-sm text-bege/85">
                <li class="flex gap-2"><x-site.icon name="map-pin" class="mt-0.5 size-4 shrink-0" /> <span>{{ $settings['address'] }}<br>{{ $settings['district'] }} – {{ $settings['city'] }}</span></li>
                <li class="flex gap-2"><x-site.icon name="phone" class="mt-0.5 size-4 shrink-0" /> <a href="{{ \App\Support\Site::whatsappUrl() }}" target="_blank" rel="noopener" data-track-location="rodape" class="hover:text-branco">{{ $settings['phone'] }}</a></li>
                @if ($settings['email'])
                    <li class="flex gap-2"><x-site.icon name="mail" class="mt-0.5 size-4 shrink-0" /> <a href="mailto:{{ $settings['email'] }}" class="break-all hover:text-branco">{{ $settings['email'] }}</a></li>
                @endif
                @if ($settings['hours'])
                    <li class="flex gap-2"><x-site.icon name="clock" class="mt-0.5 size-4 shrink-0" /> <span>{{ $settings['hours'] }}</span></li>
                @endif
            </ul>
        </div>
    </div>

    <div class="border-t border-bege/15">
        <div class="container-site flex flex-col gap-3 py-6 text-xs text-bege/90 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ date('Y') }} Instituto da Mente · CNPJ {{ $settings['cnpj'] }}</p>
            <ul class="flex flex-wrap gap-x-5 gap-y-2">
                <li><a href="{{ route('privacy') }}" class="hover:text-branco">Política de Privacidade</a></li>
                <li><a href="{{ route('terms') }}" class="hover:text-branco">Termos de Uso</a></li>
                <li><button type="button" class="hover:text-branco" onclick="window.dispatchEvent(new Event('consent:open'))">Preferências de cookies</button></li>
            </ul>
        </div>
        <p class="container-site pb-6 text-xs text-bege/85">Em situação de crise ou risco, ligue 188 (CVV, gratuito, 24h) ou procure o serviço de emergência mais próximo.</p>
    </div>
</footer>
