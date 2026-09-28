@props(['testimonial'])
<figure class="card flex h-full flex-col bg-white/70 p-6">
    <x-site.icon name="quote" class="size-7 text-bege" />
    <blockquote class="mt-4 flex-1 font-serif text-xl leading-snug text-marrom">“{{ $testimonial->content }}”</blockquote>
    <figcaption class="mt-5 text-sm"><span class="font-semibold text-marrom">{{ $testimonial->name }}</span> · {{ $testimonial->kindLabel() }}</figcaption>
</figure>
