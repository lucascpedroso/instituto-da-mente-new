@props([
    'title' => null,
    'description' => 'Instituto da Mente, em Campinas/SP: clínica de psicanálise e terapia para toda a família — individual, casal, crianças, adolescentes e idosos — e formação de psicanalistas pelo Tripé Psicanalítico.',
    'image' => null,
    'canonical' => null,
    'schema' => [],
    'breadcrumbs' => [],
    'noindex' => false,
    'type' => 'website',
])
@php
    $fullTitle = $title ? $title.' | Instituto da Mente' : 'Instituto da Mente — Terapia para toda a família | Campinas/SP';
    $description = \Illuminate\Support\Str::limit(strip_tags($description), 160);
    $image = $image ?: asset('images/og-default.png');
    $canonical = $canonical ?: url()->current();
    $tracking = [
        'pixel' => $settings['meta_pixel_id'] ?: null,
        'ga4' => $settings['ga4_id'] ?: null,
        'ads' => $settings['google_ads_id'] ?: null,
        'adsLabel' => $settings['google_ads_conversion_label'] ?: null,
    ];
    $schemas = array_merge([\App\Support\Site::organizationSchema()], $breadcrumbs ? [\App\Support\Site::breadcrumbSchema($breadcrumbs)] : [], $schema);
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">
    @if (config('instituto.static_preview'))
        <meta name="robots" content="noindex, nofollow">
    @elseif ($noindex)
        <meta name="robots" content="noindex, follow">
    @endif

    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Instituto da Mente">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#FAFAF7">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon-32.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.__SITE__ = {!! json_encode($tracking, JSON_HEX_TAG) !!};
    </script>

    @foreach ($schemas as $item)
        <script type="application/ld+json">{!! \App\Support\Site::jsonLd($item) !!}</script>
    @endforeach
</head>
<body class="flex min-h-screen flex-col">
    <a href="#conteudo" class="sr-only z-50 rounded bg-marrom px-4 py-2 text-branco focus:not-sr-only focus:fixed focus:top-3 focus:left-3">Pular para o conteúdo</a>

    @if (config('instituto.static_preview'))
        <div class="bg-preto px-4 py-2 text-center text-xs text-branco">
            <strong>Pré-visualização</strong> — os formulários estão desativados nesta versão; use o WhatsApp.
        </div>
    @endif

    <x-site.header :therapies="$navTherapies" :courses="$navCourses" />

    <main id="conteudo" class="flex-1">
        @if ($breadcrumbs)
            <x-site.breadcrumbs :items="$breadcrumbs" />
        @endif

        {{ $slot }}
    </main>

    <x-site.footer :settings="$settings" :therapies="$navTherapies" :courses="$navCourses" />

    <x-site.whatsapp-float />
    <x-site.cookie-banner />
</body>
</html>
