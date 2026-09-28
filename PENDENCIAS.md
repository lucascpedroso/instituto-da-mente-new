# Pendências de conteúdo e configuração

O site está pronto para ir ao ar, mas os itens abaixo dependem do cliente ou da equipe.
Tudo marcado com 🛠 **Painel** se resolve pelo `/admin`, sem desenvolvedor.

## Antes de publicar (bloqueantes)

- [ ] **Domínio definido.** Hoje o e-mail padrão usa `contato@institutodamente.com.br` como exemplo. Ajustar no `.env` do servidor e em 🛠 Painel › Configurações.
- [ ] **Conta Hostinger (Premium)** do cliente, com acesso SSH liberado. Ver `DEPLOY-HOSTINGER.md`.
- [ ] **E-mail `contato@<domínio>`** criado na Hostinger (recebe os leads).
- [ ] **Revisão da Política de Privacidade e dos Termos** pelo cliente ou advogado. O texto base segue a LGPD.
- [ ] **Aprovação final do Ricardo Mello** (responsável pela aprovação, conforme o formulário).
- [ ] **Posts do blog**: os 3 textos iniciais entram como rascunho. Revisar e publicar em 🛠 Painel › Blog.

## Materiais do cliente

- [ ] **Logo em vetor (SVG/PDF) ou PNG em alta resolução.** Os arquivos recebidos têm só 240 px. O site já usa o logo, mas ele fica levemente borrado em telas retina e na imagem de compartilhamento.
- [ ] **HEX/Pantone exatos da paleta.** Os valores atuais foram lidos de uma imagem de baixa resolução (o PDF já alertava).
- [ ] **Fotos profissionais**: Ricardo e equipe (🛠 Profissionais), clínica (🛠 Terapias) e capas (🛠 Livros/Cursos). Hoje aparecem monogramas e capas ilustrativas.
- [ ] **Demais profissionais**: nome, profissão, registro, bio e especialidades (🛠 Profissionais).
- [ ] **Psicólogo(a) responsável pelos laudos TDAH/TDA/TEA**: nome e CRP. A página diz "realizados por profissional habilitado".
- [ ] **Depoimentos reais** de pacientes e alunos, com autorização (🛠 Depoimentos, ou pelo formulário em `/depoimentos`).

## Informações comerciais

- [ ] **Cursos**: carga horária, duração, formato, pré-requisitos, datas de turma e investimento (🛠 Cursos). Hoje: "Consulte condições e turmas".
- [ ] **Checkout dos cursos** (opcional): se houver Hotmart, Eduzz ou Mercado Pago, colar o link em 🛠 Cursos › "Link de pagamento".
- [ ] **Livro "Psicanálise"**: link de compra e preço (🛠 Livros). Sem link, o botão abre o WhatsApp.
- [ ] **Horário de atendimento** e **CEP** (🛠 Configurações).
- [ ] **Abordagem/linha psicanalítica** a destacar, além do Tripé (pendência do PDF).
- [ ] **Provas de autoridade**: números, eventos, entrevistas e publicações (pendência do PDF). O formulário diz "podemos fazer um conteúdo".

## Marketing e rastreamento

- [ ] **Meta Pixel ID**, **GA4 (G-…)**, **Google Ads (AW-…)** e **rótulo de conversão de lead** (🛠 Configurações › Rastreamento).
  - Eventos já configurados: `PageView`, `Contact` (clique no WhatsApp) e `Lead` / `generate_lead` + conversão do Ads (envio de formulário).
- [ ] **Google Search Console**: verificar o domínio e enviar `https://<domínio>/sitemap.xml`.
- [ ] **Google Meu Negócio**: conferir se o endereço bate com o do site.
- [ ] Usar links com **UTM** nas campanhas (ex.: `?utm_source=instagram&utm_medium=bio&utm_campaign=formacao`). Os leads mostram a origem no painel.
