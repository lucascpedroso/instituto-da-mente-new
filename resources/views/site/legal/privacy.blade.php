<x-layouts.site title="Política de Privacidade" description="Como o Instituto da Mente coleta, utiliza e protege seus dados pessoais, em conformidade com a Lei Geral de Proteção de Dados (LGPD)."
                :breadcrumbs="['Política de Privacidade' => null]">
    <section class="container-site max-w-3xl py-10 sm:py-14">
        <h1 class="heading-xl">Política de Privacidade</h1>
        <p class="mt-3 text-sm text-cinza/80">Última atualização: {{ \Illuminate\Support\Carbon::parse('2026-09-28')->translatedFormat('d \d\e F \d\e Y') }}</p>

        <div class="prose-instituto mt-10">
            <p>O <strong>Instituto da Mente</strong> (CNPJ {{ \App\Models\Setting::get('cnpj') }}), com sede na {{ \App\Support\Site::fullAddress() }}, respeita a sua privacidade. Esta política explica, de forma simples, como tratamos os dados pessoais coletados por este site, em conformidade com a Lei nº 13.709/2018 — Lei Geral de Proteção de Dados Pessoais (LGPD).</p>

            <h2>1. Quais dados coletamos</h2>
            <ul>
                <li><strong>Formulários de contato, agendamento e inscrição:</strong> nome, telefone/WhatsApp, e-mail (opcional), preferência de atendimento, curso ou terapia de interesse e a mensagem que você escrever.</li>
                <li><strong>Depoimentos:</strong> nome ou iniciais, o texto do depoimento e se você é paciente ou aluno.</li>
                <li><strong>Dados de navegação:</strong> páginas visitadas e origem da visita (por exemplo, parâmetros de campanhas UTM), além de um identificador técnico do endereço IP armazenado de forma irreversível (hash), usado apenas para segurança e prevenção de abuso.</li>
                <li><strong>Cookies de medição e marketing</strong> (Google Analytics, Google Ads e Meta Pixel), <strong>somente se você autorizar</strong> no aviso de cookies.</li>
            </ul>
            <p>Pedimos que você <strong>não envie informações detalhadas sobre sua saúde</strong> pelos formulários do site. Essas questões serão tratadas diretamente com o profissional, com todo o sigilo que a relação terapêutica exige.</p>

            <h2>2. Para que usamos seus dados</h2>
            <ul>
                <li>Responder ao seu contato e agendar atendimentos (execução de procedimentos preliminares a pedido do titular — art. 7º, V, LGPD);</li>
                <li>Enviar informações sobre cursos e turmas que você solicitou;</li>
                <li>Publicar depoimentos, apenas com o seu consentimento expresso (art. 7º, I);</li>
                <li>Medir o desempenho do site e de nossas campanhas, mediante consentimento para cookies (art. 7º, I);</li>
                <li>Garantir a segurança do site e prevenir fraudes (legítimo interesse — art. 7º, IX).</li>
            </ul>

            <h2>3. Compartilhamento</h2>
            <p>Não vendemos seus dados. Eles podem ser processados por fornecedores que nos ajudam a operar o site — hospedagem (Hostinger), envio de e-mails e, se autorizado, Google e Meta para medição de campanhas — sempre com obrigações de segurança e confidencialidade. Também poderemos compartilhar dados quando exigido por lei ou por autoridade competente.</p>

            <h2>4. Cookies</h2>
            <p>Usamos cookies <strong>essenciais</strong>, necessários para o funcionamento do site (por exemplo, para enviar formulários com segurança). Os cookies de <strong>medição e marketing</strong> só são ativados se você clicar em “Aceitar todos”. Você pode mudar sua escolha a qualquer momento em “Preferências de cookies”, no rodapé do site.</p>

            <h2>5. Por quanto tempo guardamos</h2>
            <p>Os dados de contato são mantidos pelo tempo necessário para o atendimento da sua solicitação e por até 2 (dois) anos após o último contato, salvo obrigação legal ou se você se tornar paciente ou aluno — hipótese em que se aplicam os prazos próprios dessas relações. Depoimentos permanecem publicados até que você solicite a remoção.</p>

            <h2>6. Seus direitos</h2>
            <p>Você pode, a qualquer momento: confirmar se tratamos seus dados; acessá-los; corrigi-los; solicitar anonimização, bloqueio ou eliminação; revogar o consentimento; e obter informações sobre o compartilhamento. Para exercer esses direitos, fale com a gente:</p>
            <ul>
                <li>WhatsApp: {{ \App\Models\Setting::get('phone') }}</li>
                @if (\App\Models\Setting::get('email'))
                    <li>E-mail: {{ \App\Models\Setting::get('email') }}</li>
                @endif
            </ul>
            <p>Você também pode apresentar reclamação à Autoridade Nacional de Proteção de Dados (ANPD).</p>

            <h2>7. Segurança</h2>
            <p>Adotamos medidas técnicas e administrativas para proteger seus dados, como conexão criptografada (HTTPS), acesso restrito ao painel administrativo e armazenamento em servidores protegidos.</p>

            <h2>8. Alterações</h2>
            <p>Esta política pode ser atualizada. A versão vigente estará sempre disponível nesta página, com a data da última atualização.</p>
        </div>
    </section>
</x-layouts.site>
