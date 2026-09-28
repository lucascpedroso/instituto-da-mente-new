@props(['id' => 'formulario', 'message' => null])
{{-- Substitui os formulários na pré-visualização estática (GitHub Pages), que não tem servidor. --}}
<div id="{{ $id }}" class="rounded-xl bg-offwhite p-6 text-center">
    <p class="font-serif text-2xl text-marrom">Fale com a gente pelo WhatsApp</p>
    <p class="mt-2 text-sm">Nesta pré-visualização os formulários estão desativados. No site oficial, eles enviam a mensagem direto para a equipe.</p>
    <a href="{{ \App\Support\Site::whatsappUrl($message) }}" target="_blank" rel="noopener" class="btn-whatsapp mt-5"><x-site.icon name="whatsapp" /> Conversar no WhatsApp</a>
</div>
