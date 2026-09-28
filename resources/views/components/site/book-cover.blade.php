@props(['book', 'size' => 'thumb'])
@if ($book->cover)
    <img src="{{ $size === 'thumb' ? $book->thumbUrl() : $book->imageUrl() }}" alt="Capa do livro {{ $book->title }}"
         {{ $attributes->merge(['class' => 'aspect-[2/3] w-full rounded-lg object-cover shadow-xl shadow-marrom/20']) }} loading="lazy">
@else
    <div {{ $attributes->merge(['class' => 'flex aspect-[2/3] w-full flex-col justify-between rounded-lg bg-gradient-to-br from-marrom to-terracota p-6 text-branco shadow-xl shadow-marrom/20']) }}>
        <img src="{{ asset('images/logo-branco.png') }}" alt="" class="w-16 opacity-80" loading="lazy">
        <div>
            <p class="font-serif text-3xl leading-tight">{{ $book->title }}</p>
            <p class="mt-2 text-sm text-bege">{{ $book->author }}</p>
        </div>
    </div>
@endif
