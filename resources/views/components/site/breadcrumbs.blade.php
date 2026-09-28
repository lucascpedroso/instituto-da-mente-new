@props(['items'])
<nav aria-label="Você está em" class="container-site pt-6 text-sm">
    <ol class="flex flex-wrap items-center gap-1.5 text-cinza/80">
        <li><a href="{{ route('home') }}" class="hover:text-marrom">Início</a></li>
        @foreach ($items as $label => $url)
            <li class="flex items-center gap-1.5">
                <x-site.icon name="chevron-right" class="size-3.5" />
                @if ($url && ! $loop->last)
                    <a href="{{ $url }}" class="hover:text-marrom">{{ $label }}</a>
                @else
                    <span aria-current="page" class="text-marrom">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
