@props(['therapy'])
<a href="{{ route('therapies.show', $therapy) }}" class="card-link group flex flex-col bg-white/70 p-6">
    <span class="mb-5 inline-flex size-12 items-center justify-center rounded-2xl bg-offwhite text-laranja-escuro">
        <x-site.icon :name="$therapy->icon ?: 'brain'" class="size-6" />
    </span>
    <h3 class="text-2xl">{{ $therapy->title }}</h3>
    <p class="mt-2 flex-1 text-sm leading-relaxed">{{ $therapy->summary }}</p>
    <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-laranja-escuro">Saiba mais <x-site.icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" /></span>
</a>
