@props(['professional'])
@if ($professional->photo)
    <img src="{{ $professional->thumbUrl() }}" alt="{{ $professional->name }}" {{ $attributes->merge(['class' => 'object-cover']) }} loading="lazy">
@else
    <div {{ $attributes->merge(['class' => 'flex items-center justify-center bg-gradient-to-br from-bege to-marrom-claro font-serif text-branco']) }} aria-hidden="true">
        <span class="text-5xl">{{ collect(explode(' ', $professional->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}</span>
    </div>
@endif
