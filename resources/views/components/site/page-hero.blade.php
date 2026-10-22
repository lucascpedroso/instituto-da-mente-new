@props(['eyebrow' => null, 'title', 'lead' => null])
<section>
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
</section>
