@props(['therapies', 'courses'])
@php
    $links = [
        ['label' => 'Início', 'route' => 'home'],
        ['label' => 'O Instituto', 'route' => 'about'],
        ['label' => 'Terapias', 'route' => 'therapies.index', 'children' => $therapies->map(fn ($t) => ['label' => $t->title, 'url' => route('therapies.show', $t)])],
        ['label' => 'Formação', 'route' => 'courses.index', 'children' => $courses->map(fn ($c) => ['label' => $c->title, 'url' => route('courses.show', $c)])],
        ['label' => 'Profissionais', 'route' => 'professionals.index'],
        ['label' => 'Livros', 'route' => 'books.index'],
        ['label' => 'Blog', 'route' => 'blog.index'],
        ['label' => 'Contato', 'route' => 'contact'],
    ];
    $isActive = fn ($route) => request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route));
@endphp
<header x-data="{ open: false }" @keydown.escape.window="open = false"
        class="sticky top-0 z-40 border-b border-areia/50 bg-branco/90 backdrop-blur supports-[backdrop-filter]:bg-branco/80">
    <div class="container-site flex h-18 items-center justify-between gap-4 py-2">
        <a href="{{ route('home') }}" class="shrink-0" aria-label="Instituto da Mente — página inicial">
            <img src="{{ asset('images/logo-laranja.png') }}" alt="Instituto da Mente" width="201" height="110" class="h-13 w-auto sm:h-14">
        </a>

        <nav class="hidden lg:block" aria-label="Menu principal">
            <ul class="flex items-center gap-1 text-[0.9rem] font-medium">
                @foreach ($links as $link)
                    <li class="relative" @if (! empty($link['children'])) x-data="{ sub: false }" @mouseenter="sub = true" @mouseleave="sub = false" @endif>
                        @if (! empty($link['children']) && count($link['children']))
                            <button type="button" @click="sub = !sub" :aria-expanded="sub"
                                    class="flex items-center gap-1 rounded-full px-3 py-2 transition hover:text-marrom {{ $isActive($link['route']) ? 'text-marrom' : 'text-cinza' }}">
                                {{ $link['label'] }} <x-site.icon name="chevron-down" class="size-4 transition" ::class="sub && 'rotate-180'" />
                            </button>
                            <div x-cloak x-show="sub" x-transition.opacity.duration.150ms class="absolute top-full left-0 w-72 pt-2">
                                <ul class="rounded-2xl border border-areia/60 bg-branco p-2 shadow-xl shadow-marrom/10">
                                    <li><a href="{{ route($link['route']) }}" class="block rounded-xl px-3 py-2 font-semibold text-marrom hover:bg-offwhite">Ver todos</a></li>
                                    @foreach ($link['children'] as $child)
                                        <li><a href="{{ $child['url'] }}" class="block rounded-xl px-3 py-2 text-cinza hover:bg-offwhite hover:text-marrom">{{ $child['label'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <a href="{{ route($link['route']) }}" @if ($isActive($link['route'])) aria-current="page" @endif
                               class="block rounded-full px-3 py-2 transition hover:text-marrom {{ $isActive($link['route']) ? 'text-marrom' : 'text-cinza' }}">{{ $link['label'] }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ route('contact') }}#agendar" class="btn-primary hidden sm:inline-flex">Agendar sessão</a>
            <button type="button" class="rounded-full p-2 text-marrom lg:hidden" @click="open = true" aria-label="Abrir menu" :aria-expanded="open">
                <x-site.icon name="menu" class="size-7" />
            </button>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div x-cloak x-show="open" class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true" aria-label="Menu">
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-preto/40" @click="open = false"></div>
        <nav x-show="open" x-transition:enter="transition duration-200" x-transition:enter-start="translate-x-full" x-transition:leave="transition duration-150" x-transition:leave-end="translate-x-full"
             class="absolute inset-y-0 right-0 flex w-[88%] max-w-sm flex-col overflow-y-auto bg-branco p-6 shadow-2xl">
            <div class="mb-6 flex items-center justify-between">
                <img src="{{ asset('images/logo-laranja.png') }}" alt="Instituto da Mente" width="201" height="110" class="h-12 w-auto">
                <button type="button" class="rounded-full p-2 text-marrom" @click="open = false" aria-label="Fechar menu"><x-site.icon name="x" class="size-7" /></button>
            </div>
            <ul class="space-y-1 text-lg">
                @foreach ($links as $link)
                    <li @if (! empty($link['children'])) x-data="{ sub: false }" @endif>
                        @if (! empty($link['children']) && count($link['children']))
                            <button type="button" @click="sub = !sub" :aria-expanded="sub" class="flex w-full items-center justify-between rounded-xl px-3 py-3 font-medium text-marrom">
                                {{ $link['label'] }} <x-site.icon name="chevron-down" class="size-5 transition" ::class="sub && 'rotate-180'" />
                            </button>
                            <ul x-show="sub" x-collapse class="mb-2 ml-3 border-l border-areia pl-3 text-base">
                                <li><a href="{{ route($link['route']) }}" class="block py-2 font-semibold text-marrom">Ver todos</a></li>
                                @foreach ($link['children'] as $child)
                                    <li><a href="{{ $child['url'] }}" class="block py-2 text-cinza">{{ $child['label'] }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ route($link['route']) }}" class="block rounded-xl px-3 py-3 font-medium text-marrom">{{ $link['label'] }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="mt-auto space-y-3 pt-8">
                <a href="{{ route('contact') }}#agendar" class="btn-primary w-full">Agendar sessão</a>
                <a href="{{ \App\Support\Site::whatsappUrl() }}" target="_blank" rel="noopener" data-track-location="menu" class="btn-whatsapp w-full"><x-site.icon name="whatsapp" /> Falar no WhatsApp</a>
            </div>
        </nav>
    </div>
</header>
