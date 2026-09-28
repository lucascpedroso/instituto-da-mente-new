<x-layouts.site title="Termos de Uso" description="Termos de uso do site do Instituto da Mente."
                :breadcrumbs="['Termos de Uso' => null]">
    <section class="container-site max-w-3xl py-10 sm:py-14">
        <h1 class="heading-xl">Termos de Uso</h1>
        <p class="mt-3 text-sm text-cinza/80">Última atualização: {{ \Illuminate\Support\Carbon::parse('2026-09-28')->translatedFormat('d \d\e F \d\e Y') }}</p>

        <div class="prose-instituto mt-10">
            <p>Ao utilizar este site, mantido pelo <strong>Instituto da Mente</strong> (CNPJ {{ \App\Models\Setting::get('cnpj') }}), você concorda com os termos abaixo.</p>

            <h2>1. Finalidade do site</h2>
            <p>Este site apresenta os serviços clínicos, as formações e os conteúdos do Instituto da Mente e permite que você entre em contato, solicite agendamentos e manifeste interesse em cursos.</p>

            <h2>2. O conteúdo não substitui atendimento</h2>
            <p>Os textos do site e do blog têm caráter informativo e educativo e <strong>não substituem avaliação ou acompanhamento profissional</strong>. Em situação de crise ou risco, ligue para o CVV (188, gratuito, 24 horas), para o SAMU (192) ou procure o serviço de emergência mais próximo.</p>

            <h2>3. Agendamentos e inscrições</h2>
            <p>O envio de um formulário não confirma automaticamente um agendamento nem garante vaga em cursos. A confirmação ocorre após o contato da nossa equipe. Valores, formas de pagamento e condições são informados diretamente a cada interessado.</p>

            <h2>4. Propriedade intelectual</h2>
            <p>Marca, logotipo, textos, imagens e materiais deste site pertencem ao Instituto da Mente ou são utilizados com autorização. Não é permitida a reprodução sem autorização prévia, exceto o compartilhamento de links.</p>

            <h2>5. Depoimentos</h2>
            <p>Depoimentos enviados passam por moderação e podem ser editados apenas para correções ortográficas ou para preservar a privacidade de terceiros. Não publicamos conteúdo ofensivo ou discriminatório de qualquer natureza.</p>

            <h2>6. Links externos</h2>
            <p>O site pode conter links para páginas de terceiros (como WhatsApp, redes sociais ou lojas de livros). Não nos responsabilizamos pelo conteúdo ou pelas práticas de privacidade desses sites.</p>

            <h2>7. Privacidade</h2>
            <p>O tratamento de dados pessoais segue a nossa <a href="{{ route('privacy') }}">Política de Privacidade</a>.</p>

            <h2>8. Foro</h2>
            <p>Estes termos são regidos pela legislação brasileira. Fica eleito o foro da Comarca de Campinas/SP.</p>
        </div>
    </section>
</x-layouts.site>
