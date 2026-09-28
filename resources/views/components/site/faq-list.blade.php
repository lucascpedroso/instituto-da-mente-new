@props(['faqs'])
<div class="divide-y divide-areia/70 rounded-2xl border border-areia/70 bg-white/60">
    @foreach ($faqs as $faq)
        <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
            <h3 class="font-sans text-base">
                <button type="button" @click="open = !open" :aria-expanded="open"
                        class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left font-semibold text-marrom sm:px-6">
                    {{ $faq->question }}
                    <x-site.icon name="chevron-down" class="size-5 shrink-0 transition" ::class="open && 'rotate-180'" />
                </button>
            </h3>
            <div x-show="open" x-collapse>
                <p class="px-5 pb-5 leading-relaxed sm:px-6">{{ $faq->answer }}</p>
            </div>
        </div>
    @endforeach
</div>
