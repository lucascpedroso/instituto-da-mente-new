@props(['eyebrow' => null, 'title', 'lead' => null])
<section class="relative overflow-hidden">
    <div class="container-site pt-10 pb-12 sm:pt-14 sm:pb-16">
        <div class="max-w-3xl">
            @if ($eyebrow)
                <p class="eyebrow mb-4">{{ $eyebrow }}</p>
            @endif
            <h1 class="heading-xl">{{ $title }}</h1>
            @if ($lead)
                <p class="lead mt-5 max-w-2xl">{{ $lead }}</p>
            @endif
            {{ $slot }}
        </div>
    </div>
    <img src="{{ asset('images/marca.png') }}" alt="" aria-hidden="true" width="178" height="178"
         class="pointer-events-none absolute -right-16 -bottom-10 hidden w-80 opacity-[0.07] md:block">
</section>
