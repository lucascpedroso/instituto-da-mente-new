@props(['post'])
<article class="card-link group relative flex flex-col overflow-hidden bg-white/70">
    @if ($post->cover)
        <img src="{{ $post->thumbUrl() }}" alt="" class="aspect-[16/10] w-full object-cover" loading="lazy" width="640" height="400">
    @else
        <div class="flex aspect-[16/10] items-center justify-center bg-offwhite">
            <img src="{{ asset('images/marca.png') }}" alt="" class="w-24 opacity-40" loading="lazy">
        </div>
    @endif
    <div class="flex flex-1 flex-col p-6">
        <p class="mb-2 text-xs text-cinza/80">
            @if ($post->category){{ $post->category->name }} · @endif
            <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->translatedFormat('d \d\e F \d\e Y') }}</time>
        </p>
        <h3 class="text-2xl"><a href="{{ route('blog.show', $post) }}" class="after:absolute after:inset-0">{{ $post->title }}</a></h3>
        <p class="mt-2 flex-1 text-sm leading-relaxed">{{ $post->summary() }}</p>
        <span class="mt-4 text-xs text-cinza/80">{{ $post->readingTime() }} min de leitura</span>
    </div>
</article>
