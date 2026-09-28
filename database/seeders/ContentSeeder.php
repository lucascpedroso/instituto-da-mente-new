<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Professional;
use App\Models\Therapy;
use Illuminate\Database\Seeder;

/**
 * Conteúdo inicial real, extraído do briefing do cliente
 * (Formulário Ricardo, Missão/Visão/Valores e Arquitetura do Site).
 * Tudo é editável depois pelo painel /admin.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->professionals();
        $this->therapies();
        $this->courses();
        $this->books();
        $this->faqs();
        $this->posts();
    }

    private function professionals(): void
    {
        Professional::updateOrCreate(['slug' => 'ricardo-mello'], [
            'name' => 'Ricardo Mello',
            'profession' => 'Psicanalista clínico · Fundador do Instituto da Mente',
            'short_bio' => 'Psicanalista há cerca de 8 anos, fundador do Instituto da Mente e autor do livro "Psicanálise", base do material didático da nossa formação.',
            'bio' => <<<'HTML'
                <p>Desde sempre, Ricardo Mello quis construir algo para ajudar as pessoas — e encontrou na psicanálise essa oportunidade. Há cerca de 8 anos atua como psicanalista clínico, acompanhando adultos, casais, famílias, crianças e adolescentes.</p>
                <p>Em novembro de 2023 fundou o Instituto da Mente, em Campinas/SP, unindo duas missões: o cuidado da saúde emocional de toda a família e a formação de novos psicanalistas, com um ensino rigorosamente comprometido com o Tripé Psicanalítico.</p>
                <p>É autor do livro <em>Psicanálise</em>, que fundamenta o material didático próprio utilizado na formação do Instituto.</p>
                HTML,
            'education' => <<<'HTML'
                <ul>
                    <li>Formação em Psicanálise Clínica</li>
                    <li>Terapia Familiar e de Casais</li>
                    <li>Especialista em medo e ansiedade</li>
                    <li>Atendimento à população LGBTQIAP+</li>
                    <li>Saúde mental do homem</li>
                    <li>Pós-graduação em Gestão de Pessoas e Processos Gerenciais</li>
                    <li>Graduação em Gestão de Recursos Humanos</li>
                </ul>
                HTML,
            'specialties' => ['Psicanálise clínica', 'Terapia de casal', 'Terapia familiar', 'Medo e ansiedade', 'Atendimento LGBTQIAP+', 'Saúde mental do homem'],
            'is_featured' => true,
            'is_active' => true,
            'sort' => 0,
        ]);
    }

    private function therapies(): void
    {
        $common = '<h2>Como funciona</h2><p>As sessões têm duração de 50 minutos e, em geral, acontecem uma vez por semana. O atendimento pode ser presencial, em nosso espaço no Jardim Guanabara, em Campinas/SP, ou online, com o mesmo cuidado e sigilo. O primeiro passo é uma conversa inicial para entender o seu momento e combinar como será o acompanhamento.</p><p>Os valores são combinados diretamente com o Instituto, por sessão ou em pacotes, conforme a sua necessidade.</p>';

        $items = [
            ['Psicanálise individual', 'psicanalise-individual', 'brain', 'Um espaço de escuta profunda e sigilosa para compreender o que você sente, pensa e repete — e abrir caminho para mudanças reais.',
                '<p>A psicanálise é um processo de autoconhecimento. Por meio da fala livre e da escuta qualificada do analista, aquilo que parecia confuso ganha sentido: padrões que se repetem, angústias sem nome, conflitos antigos que ainda pesam no presente.</p><p>Não existe uma causa única para iniciar uma análise. Dizemos que todas as pessoas que desejam passar pelo processo de evolução do eu podem se beneficiar da terapia.</p>',
                'Adultos que desejam se conhecer melhor, lidar com angústias, conflitos, perdas, mudanças de vida ou dificuldades emocionais de qualquer natureza.'],
            ['Terapia de casal', 'terapia-de-casal', 'heart', 'Apoio para o casal se escutar de novo, compreender seus conflitos e decidir, com mais clareza, os próximos passos da relação.',
                '<p>Na terapia de casal, a relação é o centro do cuidado. O processo ajuda os parceiros a reconhecer padrões de comunicação, expectativas não ditas e feridas que se acumularam ao longo do tempo, criando um espaço seguro para que cada um seja ouvido.</p>',
                'Casais que enfrentam crises, distanciamento, dificuldades de comunicação, conflitos recorrentes ou momentos de transição, como a chegada de filhos.'],
            ['Terapia familiar', 'terapia-familiar', 'users', 'Cuidado para a família como um todo: fortalecer vínculos, melhorar o diálogo e atravessar juntos os momentos difíceis.',
                '<p>Foco na família é um dos nossos valores. Na terapia familiar, olhamos para as relações entre os membros, os papéis que cada um ocupa e a forma como os conflitos circulam, promovendo compreensão mútua e vínculos mais saudáveis.</p>',
                'Famílias que passam por conflitos, separações, lutos, mudanças ou dificuldades de convivência entre pais, filhos e outros familiares.'],
            ['Crianças e adolescentes', 'criancas-e-adolescentes', 'sprout', 'Escuta especializada para as fases de desenvolvimento, com participação e orientação dos pais ao longo do processo.',
                '<p>Crianças e adolescentes expressam o que sentem de maneiras próprias: pelo brincar, pelo desenho, pelo comportamento. O atendimento respeita a linguagem de cada fase, acolhe a criança ou o adolescente e orienta os pais, que muitas vezes são quem percebe primeiro que algo precisa de atenção.</p>',
                'Crianças e adolescentes com dificuldades emocionais, de comportamento, de relacionamento ou escolares, e pais que buscam orientação.'],
            ['Terapia com idosos', 'terapia-com-idosos', 'sun', 'Um espaço de acolhimento para as questões do envelhecer: perdas, mudanças, memórias e novos sentidos para a vida.',
                '<p>O envelhecimento traz desafios próprios — aposentadoria, lutos, alterações de saúde, mudanças nos papéis familiares. A terapia oferece escuta respeitosa e sem preconceitos para que a pessoa idosa elabore suas vivências e siga construindo projetos de vida.</p>',
                'Pessoas idosas e familiares que desejam apoio emocional diante das transformações dessa fase da vida.'],
            ['Atendimento LGBTQIAP+', 'atendimento-lgbtqiap', 'rainbow', 'Atendimento especializado, afirmativo e livre de julgamentos, com profissionais preparados para acolher a diversidade.',
                '<p>Acessibilidade e inclusão são valores inegociáveis no Instituto da Mente. Oferecemos atendimento especializado à população LGBTQIAP+, com escuta qualificada para questões de identidade, relacionamentos, família, preconceito e saúde emocional.</p>',
                'Pessoas LGBTQIAP+ e suas famílias, em qualquer momento da vida.'],
            ['Ansiedade, depressão e traumas', 'ansiedade-depressao-e-traumas', 'cloud', 'Programas específicos para lidar com ansiedade, depressão, medos, traumas e outros desafios emocionais.',
                '<p>Ansiedade constante, tristeza persistente, medos que limitam, experiências traumáticas que continuam presentes: esses sofrimentos merecem cuidado especializado. O acompanhamento ajuda a compreender a origem desses sintomas e a construir novas formas de lidar com eles.</p><p>Em situações de crise ou risco, procure imediatamente o CVV (ligue 188, gratuito, 24 horas) ou o serviço de emergência mais próximo.</p>',
                'Pessoas que convivem com ansiedade, crises de pânico, medos, tristeza, desânimo ou marcas de experiências traumáticas.'],
            ['Constelação familiar', 'constelacao-familiar', 'network', 'Terapia complementar que olha para a história e as dinâmicas familiares para ampliar a compreensão de questões atuais.',
                '<p>A constelação familiar é uma abordagem terapêutica complementar que busca revelar dinâmicas presentes no sistema familiar e que podem influenciar a vida de cada pessoa. É oferecida como complemento ao cuidado terapêutico.</p>',
                'Pessoas que desejam compreender padrões familiares e questões que parecem se repetir entre gerações.'],
            ['Hipnose', 'hipnose', 'spiral', 'Hipnoterapia conduzida por profissional qualificado, como recurso complementar no tratamento de questões emocionais.',
                '<p>A hipnose clínica é um estado de concentração e relaxamento que pode ser utilizado como recurso terapêutico complementar. É conduzida com ética, respeito e sempre com o consentimento e a participação ativa do paciente.</p>',
                'Pessoas que buscam um recurso complementar para trabalhar medos, hábitos e questões emocionais, sempre após avaliação.'],
            ['Sexologia', 'sexologia', 'flame', 'Orientação e acompanhamento para questões da sexualidade, com sigilo, respeito e sem tabus.',
                '<p>A sexualidade faz parte da saúde emocional. O atendimento em sexologia oferece um espaço seguro e sigiloso para falar sobre desejo, dificuldades, relacionamentos e identidade, individualmente ou em casal.</p>',
                'Pessoas e casais que desejam compreender e cuidar melhor da própria sexualidade.'],
            ['Desenvolvimento escolar', 'desenvolvimento-escolar', 'book', 'Acompanhamento das dificuldades de aprendizagem e da vida escolar, em parceria com a família.',
                '<p>Dificuldades na escola muitas vezes têm raízes emocionais. O acompanhamento investiga o que está por trás das dificuldades de aprendizagem, atenção ou convivência e orienta pais e, quando necessário, a escola.</p>',
                'Crianças e adolescentes com dificuldades de aprendizagem, atenção ou adaptação escolar.'],
            ['Laudos TDAH, TDA e TEA', 'laudos-tdah-tda-tea', 'clipboard', 'Avaliações para TDAH, TDA e TEA, realizadas por profissional habilitado, com devolutiva e orientação à família.',
                '<p>A avaliação para TDAH (Transtorno do Déficit de Atenção com Hiperatividade), TDA e TEA (Transtorno do Espectro Autista) é realizada por profissional legalmente habilitado para emitir laudos, com base em entrevistas, instrumentos específicos e observação.</p><p>Ao final, a família recebe uma devolutiva com orientações sobre os próximos passos.</p>',
                'Crianças, adolescentes e adultos com suspeita de TDAH, TDA ou TEA, e famílias que buscam orientação.'],
        ];

        foreach ($items as $i => [$title, $slug, $icon, $summary, $body, $forWhom]) {
            Therapy::updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'icon' => $icon,
                'summary' => $summary,
                'body' => $body.$common,
                'for_whom' => $forWhom,
                'meta_description' => $summary,
                'is_active' => true,
                'sort' => $i,
            ]);
        }
    }

    private function courses(): void
    {
        $items = [
            [
                'title' => 'Formação em Psicanálise Clínica',
                'slug' => 'formacao-em-psicanalise-clinica',
                'is_flagship' => true,
                'summary' => 'Formação completa de psicanalistas pelo Tripé Psicanalítico — Teoria, Análise Pessoal e Análise Supervisionada — com credenciamento ao Conselho Nacional de Psicanálise Clínica.',
                'body' => <<<'HTML'
                    <p>O Instituto da Mente leva a sério os pilares estabelecidos por Sigmund Freud para a formação completa do psicanalista: o <strong>Tripé Psicanalítico</strong>. Nosso curso cumpre rigorosamente este modelo.</p>
                    <h2>O Tripé Psicanalítico</h2>
                    <ol>
                        <li><strong>Teoria:</strong> conteúdo aprofundado e abrangente.</li>
                        <li><strong>Análise pessoal:</strong> a experiência de análise do próprio formando.</li>
                        <li><strong>Análise supervisionada:</strong> orientação prática no atendimento clínico.</li>
                    </ol>
                    <h2>Diferenciais da nossa formação</h2>
                    <ul>
                        <li><strong>Conteúdo próprio:</strong> material didático construído a partir do livro <em>Psicanálise</em>, escrito pelo nosso fundador, Ricardo Mello.</li>
                        <li><strong>Corpo docente:</strong> professores competentes e capacitados, com vasta experiência clínica e acadêmica.</li>
                        <li><strong>Credenciamento:</strong> somos validados e credenciados ao Conselho Nacional de Psicanálise Clínica, garantindo a seriedade e o rigor da sua formação.</li>
                    </ul>
                    <p>No Instituto da Mente, capacitamos profissionais não apenas com conhecimento, mas com a experiência prática e ética necessárias para a atuação responsável e eficaz na psicanálise.</p>
                    HTML,
                'certification' => 'Certificado de formação em Psicanálise Clínica, com credenciamento ao Conselho Nacional de Psicanálise Clínica.',
            ],
            [
                'title' => 'Formação em Hipnose',
                'slug' => 'formacao-em-hipnose',
                'summary' => 'Qualificação em hipnose clínica como recurso terapêutico, com base teórica, prática supervisionada e ética profissional.',
                'body' => '<p>Curso de qualificação em hipnose voltado a profissionais e estudantes que desejam utilizar a hipnose como recurso terapêutico complementar, com fundamentos teóricos, técnicas e prática orientada.</p>',
            ],
            [
                'title' => 'Formação em Constelação Familiar',
                'slug' => 'formacao-em-constelacao-familiar',
                'summary' => 'Aprenda os fundamentos e a prática da constelação familiar como abordagem terapêutica complementar.',
                'body' => '<p>Formação voltada à compreensão das dinâmicas do sistema familiar e à condução de constelações como abordagem terapêutica complementar, com ênfase em ética e responsabilidade.</p>',
            ],
            [
                'title' => 'Interpretação de Desenho',
                'slug' => 'interpretacao-de-desenho',
                'summary' => 'Curso de qualificação para compreender o desenho como forma de expressão emocional, especialmente no atendimento infantil.',
                'body' => '<p>O desenho é uma das linguagens mais ricas da criança. Neste curso, você aprende a observar e interpretar desenhos como recurso de escuta e compreensão emocional no contexto terapêutico.</p>',
            ],
            [
                'title' => 'Formação em Terapia de Casal',
                'slug' => 'formacao-em-terapia-de-casal',
                'summary' => 'Qualificação para o atendimento de casais: dinâmicas relacionais, comunicação, conflitos e manejo clínico.',
                'body' => '<p>Curso voltado a profissionais que desejam atender casais, abordando as dinâmicas da conjugalidade, os conflitos mais frequentes e o manejo clínico do atendimento a dois.</p>',
            ],
            [
                'title' => 'Formação em Terapia Familiar',
                'slug' => 'formacao-em-terapia-familiar',
                'summary' => 'Qualificação para o atendimento de famílias, com foco nos vínculos, nos papéis familiares e na mediação de conflitos.',
                'body' => '<p>Curso de qualificação para o atendimento da família como um todo, com foco nos vínculos, nos papéis de cada membro e na condução terapêutica de conflitos familiares.</p>',
            ],
        ];

        foreach ($items as $i => $item) {
            Course::updateOrCreate(['slug' => $item['slug']], $item + [
                'format' => 'hibrido',
                'price_text' => 'Consulte condições e turmas',
                'meta_description' => $item['summary'],
                'status' => 'publicado',
                'is_flagship' => false,
                'sort' => $i,
            ]);
        }
    }

    private function books(): void
    {
        Book::updateOrCreate(['slug' => 'psicanalise'], [
            'title' => 'Psicanálise',
            'author' => 'Ricardo Mello',
            'synopsis' => 'O livro do fundador do Instituto da Mente, base do material didático próprio utilizado na Formação em Psicanálise Clínica.',
            'description' => '<p>Escrito por Ricardo Mello, <em>Psicanálise</em> reúne os fundamentos que orientam o ensino no Instituto da Mente e é a base do material didático próprio da nossa Formação em Psicanálise Clínica.</p>',
            'category' => 'Psicanálise',
            'status' => 'publicado',
            'is_featured' => true,
            'sort' => 0,
        ]);
    }

    private function faqs(): void
    {
        $items = [
            ['clinica', true, 'Como funcionam as sessões?', 'As sessões têm duração de 50 minutos e normalmente acontecem uma vez por semana. No início, fazemos uma conversa para entender o seu momento e combinar como será o acompanhamento. A partir daí, você fala livremente e o profissional conduz a escuta de forma sigilosa e sem julgamentos.'],
            ['clinica', true, 'A terapia é para sempre?', 'Não. A duração do processo varia de pessoa para pessoa e depende dos seus objetivos e do seu momento de vida. O acompanhamento é revisto ao longo do caminho, em diálogo com o profissional, e você tem liberdade para decidir sobre a continuidade.'],
            ['clinica', true, 'O atendimento é presencial ou online?', 'Os dois. Você pode ser atendido presencialmente em nosso espaço, na Rua Camargo Pimentel, 392/394 — Jardim Guanabara, Campinas/SP — ou online, de onde estiver, com o mesmo cuidado e sigilo.'],
            ['clinica', false, 'Quem pode fazer terapia?', 'Todas as pessoas, de todas as idades. Atendemos crianças, adolescentes, adultos, idosos, casais e famílias. Não é preciso ter um problema específico: todo desejo de se conhecer e evoluir como pessoa é um bom motivo para começar.'],
            ['clinica', false, 'Como sei que é hora de procurar ajuda?', 'Quando algo pesa e se repete — angústia, ansiedade, tristeza, conflitos nos relacionamentos — ou simplesmente quando você sente vontade de evoluir enquanto pessoa. No caso das crianças, geralmente são os pais que percebem mudanças no estado emocional ou no comportamento.'],
            ['clinica', false, 'Quanto custa uma sessão?', 'Trabalhamos com pagamento por sessão ou por pacote, conforme combinado com cada paciente. Entre em contato pelo WhatsApp para conhecer as condições.'],
            ['clinica', false, 'O que eu falo na sessão fica em sigilo?', 'Sim. O sigilo é um princípio ético fundamental do nosso trabalho. Nosso ambiente é acolhedor, profissional e discreto.'],
            ['formacao', true, 'O que é o Tripé Psicanalítico?', 'É o modelo de formação estabelecido por Freud, formado por três pilares: Teoria (estudo aprofundado), Análise Pessoal (o formando passa pela própria análise) e Análise Supervisionada (orientação prática nos atendimentos clínicos). Nossa formação cumpre rigorosamente esse modelo.'],
            ['formacao', false, 'A formação é credenciada?', 'Sim. O Instituto da Mente é validado e credenciado ao Conselho Nacional de Psicanálise Clínica.'],
            ['formacao', false, 'Como faço para me inscrever?', 'Preencha o formulário de interesse na página do curso ou fale com a gente pelo WhatsApp. Enviaremos as informações sobre turmas, formato e condições de pagamento.'],
        ];

        foreach ($items as $i => [$group, $home, $question, $answer]) {
            Faq::updateOrCreate(['question' => $question], [
                'group' => $group,
                'show_on_home' => $home,
                'answer' => $answer,
                'is_active' => true,
                'sort' => $i,
            ]);
        }
    }

    /**
     * Posts iniciais sobre as dúvidas mais comuns dos pacientes.
     * Em produção entram como rascunho, para revisão do Ricardo antes de publicar.
     */
    private function posts(): void
    {
        $category = Category::firstOrCreate(['slug' => 'psicanalise'], ['name' => 'Psicanálise']);
        Category::firstOrCreate(['slug' => 'familia'], ['name' => 'Família']);
        Category::firstOrCreate(['slug' => 'formacao'], ['name' => 'Formação']);

        $author = Professional::where('slug', 'ricardo-mello')->first();
        $status = app()->isProduction() ? 'rascunho' : 'publicado';

        $items = [
            ['O que é psicanálise e como funciona uma sessão', 'o-que-e-psicanalise-e-como-funciona-uma-sessao',
                'Entenda de forma simples o que acontece em uma sessão de psicanálise e por que falar livremente transforma.',
                '<p>Muita gente tem curiosidade sobre a psicanálise, mas não sabe exatamente o que acontece dentro do consultório. A proposta é simples e, ao mesmo tempo, profunda: você fala livremente sobre o que vier à mente, e o analista escuta de forma qualificada, ajudando a dar sentido ao que parecia confuso.</p><h2>Quanto tempo dura uma sessão?</h2><p>No Instituto da Mente, as sessões têm 50 minutos e costumam acontecer uma vez por semana, presencialmente ou online.</p><h2>Preciso ter um problema grave para começar?</h2><p>Não. Qualquer pessoa que deseje se conhecer melhor e evoluir como ser humano pode se beneficiar de uma análise.</p>'],
            ['A terapia é para sempre? Entenda a duração do processo', 'a-terapia-e-para-sempre',
                'Uma das dúvidas mais comuns de quem pensa em começar: quanto tempo dura uma análise?',
                '<p>Essa é uma das perguntas que mais ouvimos. A resposta honesta é: depende. Cada pessoa chega com uma história, um momento e objetivos diferentes, e o processo respeita esse ritmo.</p><p>O acompanhamento é revisto em diálogo com o profissional, e você tem sempre liberdade para decidir sobre a continuidade. O mais importante é que a terapia faça sentido para a sua vida.</p>'],
            ['Presencial ou online: qual modalidade escolher?', 'presencial-ou-online-qual-modalidade-escolher',
                'As duas modalidades funcionam. Veja o que considerar na hora de escolher.',
                '<p>O atendimento online tornou a terapia mais acessível, e hoje é possível fazer análise de qualquer lugar. Já o presencial oferece um ambiente preparado especialmente para o encontro terapêutico.</p><p>Na escolha, considere sua rotina, a privacidade do lugar onde você estará durante a sessão online e a sua preferência pessoal. No Instituto da Mente você pode escolher — e até alternar, quando necessário.</p>'],
        ];

        foreach ($items as $i => [$title, $slug, $excerpt, $body]) {
            Post::updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => $body,
                'meta_description' => $excerpt,
                'author_id' => $author?->id,
                'category_id' => $category->id,
                'status' => $status,
                'published_at' => $status === 'publicado' ? now()->subDays(10 - $i * 3) : null,
            ]);
        }
    }
}
