<x-layouts.site title="Página não encontrada" :noindex="true">
    <section class="container-site max-w-2xl py-20 text-center sm:py-28">
        <img src="{{ asset('images/marca.png') }}" alt="" class="mx-auto w-28 opacity-70" width="178" height="178">
        <p class="eyebrow mt-8">Erro 404</p>
        <h1 class="heading-lg mt-3">Esta página não foi encontrada</h1>
        <p class="lead mt-4">O endereço pode ter mudado ou o conteúdo não está mais disponível.</p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ route('home') }}" class="btn-primary">Ir para o início</a>
            <a href="{{ route('contact') }}" class="btn-outline">Fale conosco</a>
        </div>
    </section>
</x-layouts.site>
