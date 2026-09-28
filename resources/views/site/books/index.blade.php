<x-layouts.site title="Livros" description="Livros do Instituto da Mente, incluindo Psicanálise, de Ricardo Mello — base do material didático da nossa formação."
                :breadcrumbs="['Livros' => null]">
    <x-site.page-hero eyebrow="Livros" title="Leituras para aprofundar"
                      lead="Obras do nosso fundador e publicações que fundamentam o ensino no Instituto da Mente." />

    <section class="pb-16 sm:pb-24">
        <div class="container-site">
            @if ($books->isEmpty())
                <p class="text-center">Em breve, novos títulos por aqui.</p>
            @else
                <div class="grid grid-cols-2 gap-x-6 gap-y-12 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($books as $book)
                        <article class="group relative">
                            <div class="relative transition group-hover:-translate-y-1">
                                <x-site.book-cover :book="$book" />
                                @if ($book->isSoldOut())
                                    <span class="absolute top-3 left-3 rounded-full bg-preto/80 px-3 py-1 text-xs font-semibold text-branco">Esgotado</span>
                                @endif
                            </div>
                            <h2 class="mt-5 text-2xl"><a href="{{ route('books.show', $book) }}" class="after:absolute after:inset-0">{{ $book->title }}</a></h2>
                            <p class="text-sm">{{ $book->author }}</p>
                            @if ($book->formattedPrice())
                                <p class="mt-1 text-sm font-semibold text-marrom">{{ $book->formattedPrice() }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.site>
