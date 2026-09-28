<x-layouts.site :title="$book->title.' — '.$book->author" :description="$book->synopsis" :image="$book->imageUrl()" type="book"
                :breadcrumbs="['Livros' => route('books.index'), $book->title => null]"
                :schema="[[
                    '@context' => 'https://schema.org',
                    '@type' => 'Book',
                    'name' => $book->title,
                    'author' => ['@type' => 'Person', 'name' => $book->author],
                    'description' => $book->synopsis,
                    'image' => $book->imageUrl(),
                    'inLanguage' => 'pt-BR',
                    'genre' => $book->category,
                    'url' => route('books.show', $book),
                    'offers' => $book->purchase_url ? [
                        '@type' => 'Offer',
                        'url' => $book->purchase_url,
                        'price' => $book->price,
                        'priceCurrency' => $book->price ? 'BRL' : null,
                        'availability' => $book->isSoldOut() ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
                    ] : null,
                ]]">
    <section class="container-site grid gap-12 py-10 sm:py-14 md:grid-cols-[0.7fr_1.3fr]">
        <div class="mx-auto w-full max-w-xs md:max-w-none">
            <x-site.book-cover :book="$book" size="full" />
        </div>
        <div>
            @if ($book->category)
                <p class="eyebrow mb-3">{{ $book->category }}</p>
            @endif
            <h1 class="heading-xl">{{ $book->title }}</h1>
            <p class="lead mt-2">{{ $book->author }}</p>
            <p class="mt-6 text-lg leading-relaxed">{{ $book->synopsis }}</p>

            <div class="mt-8 flex flex-wrap items-center gap-4">
                @if ($book->isSoldOut())
                    <span class="rounded-full bg-offwhite px-4 py-2 text-sm font-semibold text-marrom">Esgotado no momento</span>
                    <a href="{{ \App\Support\Site::whatsappUrl('Olá! Quero ser avisado quando o livro '.$book->title.' estiver disponível.') }}" target="_blank" rel="noopener" class="btn-outline">Avise-me quando chegar</a>
                @elseif ($book->purchase_url)
                    @if ($book->formattedPrice())
                        <span class="font-serif text-3xl text-marrom">{{ $book->formattedPrice() }}</span>
                    @endif
                    <a href="{{ $book->purchase_url }}" target="_blank" rel="noopener" class="btn-primary">Comprar o livro</a>
                @else
                    <a href="{{ \App\Support\Site::whatsappUrl('Olá! Gostaria de adquirir o livro '.$book->title.'.') }}" target="_blank" rel="noopener" data-track-location="livro" class="btn-whatsapp"><x-site.icon name="whatsapp" /> Quero adquirir</a>
                @endif
            </div>

            @if ($book->description)
                <div class="prose-instituto mt-10">{!! $book->description !!}</div>
            @endif
        </div>
    </section>

    @if ($others->isNotEmpty())
        <section class="section bg-offwhite/60">
            <div class="container-site">
                <h2 class="mb-8 text-3xl">Outros livros</h2>
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                    @foreach ($others as $other)
                        <a href="{{ route('books.show', $other) }}" class="group block">
                            <x-site.book-cover :book="$other" class="transition group-hover:-translate-y-1" />
                            <p class="mt-3 font-serif text-xl text-marrom">{{ $other->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.site>
