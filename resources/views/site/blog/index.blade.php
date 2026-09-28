@php
    $title = $category ? $category->name.' — Blog' : ($search ? 'Busca: '.$search : 'Blog');
@endphp
<x-layouts.site :title="$title" description="Artigos do Instituto da Mente sobre psicanálise, saúde emocional, família e formação de psicanalistas."
                :breadcrumbs="$category ? ['Blog' => route('blog.index'), $category->name => null] : ['Blog' => null]"
                :noindex="$search !== '' || $posts->currentPage() > 1">
    <x-site.page-hero eyebrow="Blog" :title="$category ? $category->name : 'Conteúdos para refletir'"
                      lead="Textos sobre psicanálise, saúde emocional, relações familiares e formação — para ajudar você a entender melhor a si e a quem você ama." />

    <section class="pb-16 sm:pb-24">
        <div class="container-site">
            <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <ul class="flex flex-wrap gap-2 text-sm">
                    <li><a href="{{ route('blog.index') }}" class="{{ ! $category ? 'bg-marrom text-branco' : 'border border-areia bg-white text-marrom hover:border-marrom' }} inline-block rounded-full px-4 py-2">Todos</a></li>
                    @foreach ($categories as $item)
                        <li><a href="{{ route('blog.category', $item) }}" class="{{ $category?->is($item) ? 'bg-marrom text-branco' : 'border border-areia bg-white text-marrom hover:border-marrom' }} inline-block rounded-full px-4 py-2">{{ $item->name }}</a></li>
                    @endforeach
                </ul>
                <form method="GET" action="{{ $category ? route('blog.category', $category) : route('blog.index') }}" role="search" class="relative w-full md:w-72">
                    <label for="busca" class="sr-only">Buscar no blog</label>
                    <input id="busca" type="search" name="busca" value="{{ $search }}" placeholder="Buscar no blog" class="field rounded-full py-2.5 pr-11">
                    <button type="submit" class="absolute top-1/2 right-3 -translate-y-1/2 text-marrom" aria-label="Buscar"><x-site.icon name="search" /></button>
                </form>
            </div>

            @if ($posts->isEmpty())
                <div class="rounded-2xl bg-offwhite p-10 text-center">
                    <p class="font-serif text-2xl text-marrom">{{ $search ? 'Nenhum artigo encontrado para “'.$search.'”.' : 'Em breve, novos conteúdos por aqui.' }}</p>
                    @if ($search)
                        <a href="{{ route('blog.index') }}" class="btn-outline mt-6">Ver todos os artigos</a>
                    @endif
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-site.post-card :post="$post" />
                    @endforeach
                </div>
                {{ $posts->links('components.site.pagination') }}
            @endif
        </div>
    </section>
</x-layouts.site>
