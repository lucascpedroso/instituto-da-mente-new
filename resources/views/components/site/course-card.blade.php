@props(['course'])
<a href="{{ route('courses.show', $course) }}" class="card-link group flex flex-col overflow-hidden bg-white/70">
    @if ($course->cover)
        <img src="{{ $course->thumbUrl() }}" alt="" class="aspect-[16/9] w-full object-cover" loading="lazy" width="640" height="360">
    @else
        <div class="flex aspect-[16/9] items-center justify-center bg-gradient-to-br from-offwhite to-bege">
            <x-site.icon name="graduation" class="size-12 text-marrom/60" />
        </div>
    @endif
    <div class="flex flex-1 flex-col p-6">
        <p class="eyebrow mb-2">{{ $course->formatLabel() }}@if ($course->workload_hours) · {{ $course->workload_hours }}h @endif</p>
        <h3 class="text-2xl">{{ $course->title }}</h3>
        <p class="mt-2 flex-1 text-sm leading-relaxed">{{ $course->summary }}</p>
        <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-laranja-escuro">Conhecer o curso <x-site.icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" /></span>
    </div>
</a>
