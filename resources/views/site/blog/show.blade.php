@php
    $breadcrumbs = ['Blog' => route('blog.index')]
        + ($post->category ? [$post->category->name => route('blog.category', $post->category)] : [])
        + [$post->title => null];
@endphp
<x-layouts.site :title="$post->meta_title ?: $post->title" :description="$post->meta_description ?: $post->summary()" :image="$post->imageUrl()" type="article"
                :breadcrumbs="$breadcrumbs"
                :schema="[[
                    '@context' => 'https://schema.org',
                    '@type' => 'BlogPosting',
                    'headline' => $post->title,
                    'description' => $post->meta_description ?: $post->summary(),
                    'image' => $post->imageUrl(),
                    'datePublished' => $post->published_at?->toIso8601String(),
                    'dateModified' => $post->updated_at?->toIso8601String(),
                    'author' => $post->author ? ['@type' => 'Person', 'name' => $post->author->name, 'url' => route('professionals.show', $post->author)] : ['@id' => url('/').'#organizacao'],
                    'publisher' => ['@id' => url('/').'#organizacao'],
                    'mainEntityOfPage' => route('blog.show', $post),
                    'inLanguage' => 'pt-BR',
                ]]">
    <article>
        <header class="container-site max-w-3xl pt-8 pb-10 sm:pt-12">
            @if ($post->category)
                <a href="{{ route('blog.category', $post->category) }}" class="eyebrow">{{ $post->category->name }}</a>
            @endif
            <h1 class="heading-xl mt-4">{{ $post->title }}</h1>
            @if ($post->excerpt)
                <p class="lead mt-5">{{ $post->excerpt }}</p>
            @endif
            <div class="mt-7 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-cinza/90">
                @if ($post->author)
                    <a href="{{ route('professionals.show', $post->author) }}" class="flex items-center gap-2 font-medium text-marrom hover:underline">
                        <x-site.avatar :professional="$post->author" class="size-9 rounded-full text-[0.5rem]" /> {{ $post->author->name }}
                    </a>
                @endif
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d \d\e F \d\e Y') }}</time>
                <span>{{ $post->readingTime() }} min de leitura</span>
            </div>
        </header>

        @if ($post->cover)
            <div class="container-site max-w-5xl">
                <img src="{{ $post->imageUrl() }}" alt="" class="aspect-[16/9] w-full rounded-3xl object-cover" width="1600" height="900">
            </div>
        @endif

        <div class="container-site max-w-3xl py-12">
            <div class="prose-instituto">{!! $post->body !!}</div>

            @if ($post->tags->isNotEmpty())
                <ul class="mt-10 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <li class="rounded-full bg-offwhite px-3 py-1 text-xs text-marrom">#{{ $tag->name }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-areia/70 pt-6 text-sm">
                <span class="flex items-center gap-2 font-medium text-marrom"><x-site.icon name="share" class="size-4" /> Compartilhar:</span>
                <a href="https://wa.me/?text={{ rawurlencode($post->title.' '.route('blog.show', $post)) }}" target="_blank" rel="noopener" class="rounded-full border border-areia px-3 py-1.5 hover:border-marrom">WhatsApp</a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode(route('blog.show', $post)) }}" target="_blank" rel="noopener" class="rounded-full border border-areia px-3 py-1.5 hover:border-marrom">Facebook</a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ rawurlencode(route('blog.show', $post)) }}" target="_blank" rel="noopener" class="rounded-full border border-areia px-3 py-1.5 hover:border-marrom">LinkedIn</a>
            </div>
        </div>
    </article>

    <x-site.cta-band title="Quer conversar sobre isso?" text="Se este texto tocou em algo importante para você, estamos aqui para ouvir. Agende uma conversa inicial." />

    @if ($related->isNotEmpty())
        <section class="section pt-0">
            <div class="container-site">
                <h2 class="mb-8 text-3xl">Continue lendo</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($related as $item)
                        <x-site.post-card :post="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.site>
